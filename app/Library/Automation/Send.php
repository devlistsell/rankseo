<?php

namespace Acelle\Library\Automation;

use Acelle\Model\Email;
use Acelle\Model\Automation2;
use Exception;
use Carbon\Carbon;

class Send extends Action
{
    /*****

        Send action may result in the following cases:
          + Send OK (email queued, do not care about delivery status)
          + Job dispatched (return false, wait until the related job updates this action sent_at value)
          + Exception (email UID not found for example, other exception...)
        In case of Exception, it is better to stop the whole automation process and write error log to the automation
        so that the responsible person can check it

        Then, "last_executed" is used as a flag indicating that the process is done
        Execution always returns TRUE

    ****/

    protected function doExecute($manually)
    {
        /*** do not care if subscriber is active() or not ***/
        /*
        if (!$subscriber->isActive()) {
            $this->logger->warning(sprintf('Subscriber "%s" is not active (current status: "%s")', $subscriber->email, $subscriber->status));

            return false;
        }
        */

        if ($this->options['init'] == 'false' || $this->options['init'] == false) {
            throw new Exception(trans('messages.automation.email.error.not_set_up'));
        }

        $email = $this->getEmail();
        $subscriber = $this->autoTrigger->subscriber;

        if (config('app.demo') == 'true') {
            // do not wait
        } else {
            // to avoid same date/time with previous wait, wrong timeline order
            sleep(1);
        }

        if (is_null($this->getOption('queued_at'))) {
            if ($manually) {
                $queue = ACM_QUEUE_TYPE_HIGH;
                $this->logger->info('Manually executed (or via ListSubscription), choose priority queue');
            } else {
                $queue = null;
            }

            $jobId = $this->queue($queue);
        } elseif (is_null($this->getOption('sent_at'))) {
            // Job dispatched, but not finish
            $this->logger->info(sprintf('Wait for email entitled "%s" to "%s", dispatched at %s', $email->subject, $this->autoTrigger->subscriber->email, $this->getOption('queued_at')));
            return false;
        } else {
            $this->logger->info(sprintf('Sent noticed! great! "%s" to "%s"', $email->subject, $this->autoTrigger->subscriber->email));
            return true;
        }
    }

    public function getEmail()
    {
        $email = Email::findByUid($this->options['email_uid']);
        if (is_null($email)) {

            $this->setOption('email_uid', null);
            $this->autoTrigger->automation2->updateAction($this);

            throw new \Exception(sprintf("Cannot find email with UID %s for Action ID %s, title: '%s', AutoTrigger: #%s", $this->options['email_uid'], $this->getId(), $this->getTitle(), $this->autoTrigger->id));
        }
        return $email;
    }

    public function fixInvalidEmailUid()
    {
        $this->setOption('email_uid', null);
        $this->setOption('init', false);
    }

    public function getActionDescription()
    {
        $nameOrEmail = $this->autoTrigger->subscriber->getFullNameOrEmail();

        return sprintf('User %s receives email entitled "%s"', $nameOrEmail, $this->getEmail()->subject);
    }

    public function getProgressDescription($timezone = null, $locale = null)
    {
        $email = $this->getEmail();
        $subscriber = $this->autoTrigger->subscriber;

        if (!is_null($this->getLastExecuted())) {
            $queuedAt = Carbon::parse($this->getOption('queued_at'));
            $sentAt = Carbon::parse($this->getOption('sent_at'));
            $diffInSeconds = $sentAt->diffInSeconds($queuedAt);

            $sentAt->timezone($timezone);

            return trans('messages.automation.trigger.send.progress.sent', [
                'e' => $email->subject,
                'c' => $subscriber->email,
                'dif' => $sentAt->diffForHumans(),
                'dt' => format_datetime($sentAt, 'datetime_full_with_timezone', $locale),
                'sec' => $diffInSeconds
            ]);

        } elseif ($this->getOption('queued_at')) {
            $queuedAt = Carbon::parse($this->getOption('queued_at'))->timezone($timezone);

            if ($this->getOption('error')) {
                return sprintf('Error sending email "%s" to %s. Job #%s queued %s at %s. ERROR: %s', $email->subject, $subscriber->email, $this->getOption('delivery_job_id'), $queuedAt->diffForHumans(), $queuedAt->toString(), $this->getOption('error'));
            } else {
                $queueMsg = trans('messages.automation.trigger.send.progress.queued', [
                    'e' => $email->subject,
                    'c' => $subscriber->email,
                    'dif' => $queuedAt->diffForHumans(),
                    'dt' => format_datetime($queuedAt, 'datetime_full_with_timezone', $locale),
                    'id' => $this->getOption('delivery_job_id')
                ]);


                if (!empty($this->getOption('delay_note'))) {
                    $queueMsg .= '. '.$this->getOption('delay_note');
                }

                return $queueMsg;
            }
        }
    }

    public function isDelayAction()
    {
        return true;
    }

    public function retry($manually = false)
    {
        // Clean up error
        $this->setOption('queued_at', null);
        $this->setOption('error', null);
        $this->autoTrigger->setError(false, $save = false); // wait for the save() method below to save
        $this->setOption('delivery_job_id', null);
        $this->setOption('delay_note', null);
        $this->save();

        // Execute it too
        $this->execute($manually);
    }

    public function setError($errorMsg)
    {
        $this->setOption('error', $errorMsg);
        $this->autoTrigger->setError(true, $save = false);
        $this->save();
    }

    public function setSent()
    {
        $this->setOption('sent_at', now()->toString());
        $this->setOption('error', null);
        $this->setOption('delay_note', null);
        $this->autoTrigger->setError(false, $save = false);
        $this->save();
    }

    public function queue($queue)
    {
        $triggerName = $this->autoTrigger->getTrigger()->getOption('key');

        if (is_null($queue)) {
            // Higher priority for certain types of trigger
            $queueMap = [
                Automation2::TRIGGER_TYPE_WELCOME_NEW_SUBSCRIBER => ACM_QUEUE_TYPE_HIGH,
                Automation2::TRIGGER_PARTICULAR_DATE => null,
                Automation2::TRIGGER_TYPE_SAY_GOODBYE_TO_SUBSCRIBER => ACM_QUEUE_TYPE_HIGH,
                Automation2::TRIGGER_TYPE_SAY_HAPPY_BIRTHDAY => ACM_QUEUE_TYPE_HIGH,
                Automation2::TRIGGER_API => ACM_QUEUE_TYPE_HIGH,
                Automation2::TRIGGER_WEEKLY_RECURRING => null,
                Automation2::TRIGGER_MONTHLY_RECURRING => null,
                Automation2::TRIGGER_SUBSCRIPTION_ANNIVERSARY_DATE => ACM_QUEUE_TYPE_HIGH,
                Automation2::TRIGGER_WOO_ABANDONED_CART => ACM_QUEUE_TYPE_HIGH,
                Automation2::TRIGGER_TAG_BASED => ACM_QUEUE_TYPE_HIGH,
            ];

            $queue = $queueMap[$triggerName] ?? null;
        }

        $campaign = $this->getEmail();
        $jobId = $campaign->queueDeliverTo($this->autoTrigger->subscriber, $this->autoTrigger->id, $this->getId(), $queue);
        $this->setOption('queued_at', now()->toString()); // Store UTC value
        $this->setOption('error', null); // Store UTC value
        $this->setOption('delivery_job_id', $jobId); // Store UTC value

        // IMPORTANT: update action BEFORE dispatching job. In case job executes so fast! it even write to sent_at field before writing to queue_at
        // Or, if the update function is wrapped in an atomic action then it is oke
        $this->save();

        $this->logger->info(sprintf('Queue email entitled "%s" to "%s" on queue "%s", job id %s', $campaign->subject, $this->autoTrigger->subscriber->email, $queue ?? '-', $jobId));

        return $jobId;
    }
}

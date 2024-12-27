<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Acelle\Model\AutoTrigger as AutoTriggerModel;
use Acelle\Model\Email;
use Exception;

class AutoTrigger extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $trigger = AutoTriggerModel::find($request->id);
        $info = [];
        $info[] = sprintf('This is an auto trigger for automation {{ %s }}', $trigger->automation2->name);
        $info[] = sprintf('Subscriber {{ %s }}', $trigger->subscriber->email);

        $actions = [];
        $trigger->getActions(function ($a) use (&$actions, $trigger) {
            $description = "+ [". (($a->getLastExecuted()) ? "Executed" : "Waiting") . "] " .  $a->getId() . ": " . $a->getTitle();
            if ($a->isCondition()) {
                $description .= " (". $a->getEvaluationResult() .")";
            }

            $actions[] = $description;
        });

        $info[] = implode('<br>', $actions);

        echo implode('<br>', $info);
    }

    public function check(Request $request)
    {
        $trigger = AutoTriggerModel::find($request->id);

        // Execute AutoTrigger#check
        // Notice that calling check() directly against AutoTrigger will not update automation's lastError
        $trigger->check($manually = true);
        return response()->json($trigger->toJson());
    }

    public function retry(Request $request)
    {
        $trigger = AutoTriggerModel::find($request->auto_trigger_id);
        $action = $trigger->getActionById($request->action_id);

        if ($request->isMethod('get')) {
            // popup
            return view('auto_triggers.retry', [
                'trigger' => $trigger,
                'action' => $action,
            ]);
        } else {
            $action->retry($manually = true);
            return $action->toJson();
        }
    }
}

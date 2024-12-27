<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home Page - {{ get_app_name() }}</title>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/x-icon" href="https://mailcaller.de/public/images/mail-img/favicon.png">
    <link rel="stylesheet" href="{{ asset('/custom.css') }}">
</head>
<body>

<div class="mail-haeder">
        <div class="mail-haeder-inn">
            <div class="logo-box">
                <img src="https://mailcaller.de/public/images/mail-img/mailcaller-logo-color.svg" alt="Mail Caller">
            </div>
            <div class="navigation">
                <ul>
                    <li><a href="/">Start</a></li>
                    <li><a href="#features">Was es kann</a></li>
                    <li><a href="#plan">Was es kostet</a></li>
                    <li><a href="#faq">Was es ist</a></li>
                    <li><a href="#contact-us">Kontakt</a></li>
                </ul>
            </div>
            <div class="login-btn">
               <a href="https://mailcaller.de/login"> <button type="submit">Registrieren/Anmelden</button></a>
            </div>
        </div>
    </div>
    <div class="mobile-header">
        <div class="logo-box-new">
                <img src="https://mailcaller.de/public/images/mail-img/mailcaller-logo-color.svg" alt="Mail Caller">
            </div>
            <div id="mySidenav" class="sidenav">
                <img src="https://mailcaller.de/public/images/mail-img/mailcaller-logo-color.svg" alt="Mail Caller">
               <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                <a href="/">Start</a>
                <a href="#features">Was es kann</a>
                <a href="#plan">Was es kostet</a>
                <a href="#faq">Was es ist</a>
                <a href="#contact-us">Kontakt</a>
            </div>
            <div class="login-btn-new">
                <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776;</span>
            </div>
    </div>
<div class="container-out">
    <div class="container-inn">
        <div class="container-inn-box">
            <h1>So schön kann E-Mail-Marketing sein.</h1>
            <ul>
                <li class="container-inn-list">Schaffen Sie Umsatzanreize auf clevere Art und Weise.</li>
                <li class="container-inn-list">Erreichen Sie Ihre Zielgruppe direkt und persönlich.</li>
                <li class="container-inn-list">Optimieren Sie Ihre Kampagnen nach Herzenslust.</li>
            </ul>
            <a href="https://mailcaller.de/login">Vorabzugang erhalten</a>
        </div>
        <div class="banner-img">
            <img src="https://mailcaller.de/public/images/mail-img/light_mod.webp" alt="Mail Caller">
        </div>
    </div>
</div>
<div class="container-out-one" id="features">
    <div class="container-inn-one">
        <div class="container-box-one">
            <h2>Was kann Mailcaller?</h2>
            <h3>Das umfassende Tool für Newsletter-Erfolg</h3>
            <p>Mit Mailcaller gestalten, versenden und analysieren Sie E-Mail-Kampagnen besonders reibungslos und effizient.</p>
        </div>
        <div class="container-box-one-row">
            <div class="features-box">
                <div class="icon-box">
                    <img src="https://mailcaller.de/public/images/mail-img/3d-select-solid.webp" alt="Mail Caller">
                </div>
                <div class="features-box-text">
                    <h6>Intuitiver Drag-and-Drop-Editor</h6>
                    <p>Erstellen Sie professionelle Newsletter ohne Programmierkenntnisse – schnell, einfach und mit kreativer Freiheit.</p>
                </div>  
            </div>
            <div class="features-box">
                <div class="icon-box">
                    <img src="https://mailcaller.de/public/images/mail-img/edit.webp" alt="Mail Caller">
                </div>
                <div class="features-box-text">
                    <h6>Automatisierte Versandpläne</h6>
                    <p>Planen Sie E-Mails im Voraus und erreichen Sie Ihre Zielgruppe zur richtigen Zeit – rund um die Uhr.</p>
                </div>  
            </div>
            <div class="features-box">
                <div class="icon-box">
                    <img src="https://mailcaller.de/public/images/mail-img/google-docs.webp" alt="Mail Caller">
                </div>
                <div class="features-box-text">
                    <h6>Zielgruppensegmentierung</h6>
                    <p>Passen Sie Inhalte präzise an spezifische Kundengruppen an und steigern Sie so die Relevanz Ihrer Nachrichten.</p>
                </div>  
            </div>
        </div>
        <div class="container-box-one-row">
            <div class="features-box">
                <div class="icon-box">
                    <img src="https://mailcaller.de/public/images/mail-img/laptop-charging.webp" alt="Mail Caller">
                </div>
                <div class="features-box-text">
                    <h6>A/B-Testing für Optimierung</h6>
                    <p>Vergleichen Sie verschiedene Versionen Ihrer Kampagne, um die beste Performance zu ermitteln und kontinuierlich zu verbessern.</p>
                </div>  
            </div>
            <div class="features-box">
                <div class="icon-box">
                    <img src="https://mailcaller.de/public/images/mail-img/lifebelt.webp" alt="Mail Caller">
                </div>
                <div class="features-box-text">
                    <h6>Echtzeit-Analyse und Berichte</h6>
                    <p>Verfolgen Sie Öffnungs-, Klick- und Conversion-Raten live und gewinnen Sie wertvolle Erkenntnisse für künftige Kampagnen.</p>
                </div>  
            </div>
            <div class="features-box">
                <div class="icon-box">
                    <img src="https://mailcaller.de/public/images/mail-img/transition-up.webp" alt="Mail Caller">
                </div>
                <div class="features-box-text">
                    <h6>DSGVO-konformes Datenmanagement</h6>
                    <p>Mailcaller gewährleistet Datenschutz und Datensicherheit, damit Sie sich auf gesetzeskonformes und sicheres Marketing verlassen können.</p>
                </div>  
            </div>
        </div>
    </div>
</div>
<div class="container-out-one" id="plan">
    <div class="container-inn-two">
        <div class="container-box-one">
            <h2>Plans</h2>
            <h3>Tailored pricing plans designed for you</h3>
            <p>All plans include 40+ advanced tools and features to boost your product. the best plan to fit your needs.</p>
        </div>
        <div class="container-tow-row">
            <div class="rate-box">
                <div class="rate-inn rate-inn-aa">
                    <div class="rate-img">
                        <img src="https://mailcaller.de/public/images/mail-img/pricing-illustration-1.png" alt="Mail Caller">
                    </div>
                    <div class="rate-tittle">
                        <h4>Basic</h4>
                        <p>Ideal für Einzelunternehmer und Einsteiger</p>
                    </div>
                    <div class="rate-price">
                        <p><span class="sign">Ab</span> <span class="rate-no"> 9</span>€ / Monat</p>
                    </div>
                    <div class="rate-content rate-content-new">
                        <p>Jetzt starten und profitieren von:</p>
                        <ul class="rate-list">
                            <li>10.000 E-Mails pro Monat</li>
                            <li>Individuell anpassbaren E-Mail-Vorlagen</li>
                            <li>Drag-and-Drop-Editor für einfache Newsletter-Gestaltung</li>
                            <li>Grundlegender Analysefunktionen (z. B. Öffnungsraten)</li>
                            <li>DSGVO-konformem Datenmanagement</li>
                        </ul>

                        
                    </div>
                    <a href="https://mailcaller.de/login">Jetzt buchen</a>
                </div>
            </div>
            <div class="rate-box rate-box-new">
                <div class="rate-inn">
                    <span class="popular-text">Popular</span>
                    <div class="rate-img">
                        <img src="https://mailcaller.de/public/images/mail-img/pricing-illustration-2.png" alt="Mail Caller">
                    </div>
                    <div class="rate-tittle">
                        <h4>Standard</h4>
                        <p>Die perfekte Wahl für wachsende Unternehmen</p>
                    </div>
                    <div class="rate-price">
                        <p><span class="sign">Ab</span> <span class="rate-no"> 29</span>€ / Monat</p>
                    </div>
                    <div class="rate-content">
                        <p>Jetzt upgraden und alle Basic-Funktionen sowie:</p>
                        <ul class="rate-list">
                            <li>50.000 E-Mails pro Monat</li>
                            <li>Keine Mailcaller-Werbung im Fußbereich</li>
                            <li>Zielgruppensegmentierung für personalisierte Inhalte</li>
                            <li>A/B-Tests für bessere Kampagnen-Performance</li>
                            <li>24/7 E-Mail-Support</li>
                        </ul>

                        
                    </div>
                    <a href="https://mailcaller.de/login">Jetzt buchen</a>
                </div>
            </div>
            <div class="rate-box">
                <div class="rate-inn rate-inn-a">
                    <div class="rate-img">
                        <img src="https://mailcaller.de/public/images/mail-img/pricing-illustration-3.png" alt="Mail Caller">
                    </div>
                    <div class="rate-tittle">
                        <h4>Enterprise</h4>
                        <p>Für Unternehmen mit individuellen Anforderungen</p>
                    </div>
                    <div class="rate-price">
                        <p><span class="sign">Individuelles Pricing</p>
                    </div>
                    <div class="rate-content">
                        <p>Demo anfordern und alle Standard-Funktionen sowie:</p>
                        <ul class="rate-list">
                            <li>Unbegrenzte E-Mail-Anzahl und Kontakte</li>
                            <li>Subaccount-Management für Team-Kollaboration</li>
                            <li>Persönlicher Onboarding-Service</li>
                            <li>Sicherheit auf Enterprise-Niveau (z. B. SSO)</li>
                            <li>Premium-Support mit persönlichem Ansprechpartner</li>
                        </ul>

                        
                    </div>
                    <a href="https://mailcaller.de/login" >Jetzt buchen</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-out-one" id="faq">
    <div class="container-inn-two">
        <div class="container-box-one">
            <h2>Fragen sind da, um gestellt zu werden.</h2>
            <h3>Unsere Frequently Asked Questions</h3>
            <p>Und wir geben Ihnen gerne die richtigen Antworten. Hier finden Sie alle Infos zu allgemeinen Fragen, Funktionen und Features, Technik und Sicherheit, Preisen und Tarifen, Reporting und Erfolgsmessung sowie Problemlösung und Support.</p>
        </div>
        <div class="faq-row">
            <div class="tab">
                <button class="tablinks" onclick="openCity(event, 'London')" id="defaultOpen"> Allgemeine Fragen</button>
                <button class="tablinks" onclick="openCity(event, 'Paris')">Funktionen und Features</button>
                <button class="tablinks" onclick="openCity(event, 'Tokyo')">Technik und Sicherheit</button>
                <button class="tablinks" onclick="openCity(event, 'Tokyoo')">Preise und Tarife</button>
                <button class="tablinks" onclick="openCity(event, 'Tokyooo')">Reporting und Erfolgsmessung</button>
                <button class="tablinks" onclick="openCity(event, 'Tokyoooo')">Problemlösung und Support</button>
            </div>

            <div id="London" class="tabcontent">
                <h3>Allgemeine Fragen</h3>
                <div class="faq-one">
                    <button class="accordion">Mailcaller, was ist das überhaupt? </button>
                    <div class="panel-new">
                        <p>Mailcaller ist ein innovatives Tool für den automatisierten E-Mail-Versand, das Unternehmen dabei unterstützt, professionelle Newsletter zu erstellen und zu versenden, ihre Zielgruppe zu erreichen und den Erfolg ihrer E-Mail-Marketing-Kampagnen zu analysieren. Dabei kombinieren wir Benutzerfreundlichkeit mit leistungsstarken Features, um Ihrer E-Mail-Marketing-Strategie den richtigen Schub zu verleihen.</p>
                    </div>

                    <button class="accordion">Wer kann Mailcaller nutzen?</button>
                    <div class="panel-new">
                        <p>Unser praktisches Tool ist für Unternehmen jeder Größe, Einzelunternehmer, Blogger und Marketing-Agenturen geeignet, die ihre Kunden effektiv per E-Mail erreichen und über Neuigkeiten oder Rabattaktionen informieren möchten. Lassen Sie sich heute noch kostenfrei vom Mailcaller-Team beraten und holen Sie sich Ihr individuelles Angebot.</p>
                    </div>
                     
                    <button class="accordion accordion-new">Ist Newsletter-Marketing in Deutschland noch sinnvoll?</button>
                    <div class="panel-new">
                        <p>Ja, E-Mail-Marketing bleibt in Deutschland ein wirkungsvolles Instrument zur Kundenansprache und Umsatzsteigerung. 2024 wird der Umsatz im E-Mail-Werbemarkt auf 420 Millionen Euro geschätzt, mit einer jährlichen Wachstumsrate von 2,71 % bis 2029. Zudem nutzen 75,8 % der Deutschen regelmäßig E-Mails. Die Öffnungsraten variieren je nach Branche; 2023 lag die höchste Rate im Tourismusbereich bei 47,7 %. </p>
                    </div>
                </div>
            </div>

            <div id="Paris" class="tabcontent">
                <h3>Funktionen und Features</h3>
                <div class="faq-one">
                    <button class="accordion">Welche Funktionen bietet Mailcaller? </button>
                    <div class="panel-new">
                        <p>Mailcaller bietet zahlreiche Funktionen, die E-Mail-Marketing einfach und effektiv machen: Ein benutzerfreundlicher Drag-and-Drop-Editor ermöglicht die Gestaltung ansprechender Newsletter. Automatisierte Versandpläne und Zielgruppensegmentierung erleichtern die Ansprache passender Empfänger. Durch A/B-Tests können Kampagnen optimiert werden, während Echtzeit-Analyse-Tools detaillierte Einblicke in den Erfolg bieten. Zudem gewährleistet Mailcaller ein vollständig DSGVO-konformes Datenmanagement.</p>
                    </div>

                    <button class="accordion">Kann ich eigene Vorlagen für Newsletter nutzen?</button>
                    <div class="panel-new">
                        <p>Ja, Mailcaller unterstützt sowohl vorgefertigte Vorlagen als auch die Möglichkeit, eigene Vorlagen hochzuladen oder von Grund auf zu gestalten.</p>
                    </div>
                     
                    <button class="accordion accordion-new">Unterstützt Mailcaller die Integration mit anderen Tools?</button>
                    <div class="panel-new">
                        <p>Mailcaller lässt sich mit einer Vielzahl von CRM- und Marketing-Tools integrieren, darunter z. B. WordPress, WooCommerce, Zendesk, Salesforce, HubSpot oder JTL.</p>
                    </div>
                </div> 
            </div>

            <div id="Tokyo" class="tabcontent">
                <h3>Technik und Sicherheit</h3>
                <div class="faq-one">
                    <button class="accordion">Muss ich programmieren können, um Mailcaller zu nutzen?</button>
                    <div class="panel-new">
                        <p>Nein, Mailcaller ist so konzipiert, dass es ohne technische Kenntnisse genutzt werden kann. Der Drag-and-Drop-Editor und die intuitive Benutzeroberfläche machen die Erstellung von E-Mail-Marketing-Kampagnen kinderleicht.</p>
                    </div>

                    <button class="accordion">Wie sicher ist Mailcaller?</button>
                    <div class="panel-new">
                        <p>Sicherheit hat bei uns jederzeit oberste Priorität. Mailcaller nutzt moderne Sicherheitsstandards, einschließlich der Verschlüsselung von Datenübertragungen, um die Sicherheit Ihrer Daten zu gewährleisten. Zudem ist das Mailing-Tool vollständig DSGVO-konform.</p>
                    </div>
                     
                    <button class="accordion accordion-new">Kann ich Mailcaller auf mobilen Geräten nutzen?</button>
                    <div class="panel-new">
                        <p>Natürlich. Unser modernes Newsletter-Tool ist vollständig mobil optimiert und kann auf Smartphones und Tablets genutzt werden.</p>
                    </div>
                </div>
            </div>

            <div id="Tokyoo" class="tabcontent">
                <h3>Preise und Tarife</h3>
                <div class="faq-one">
                    <button class="accordion">Gibt es eine kostenlose Version von Mailcaller?</button>
                    <div class="panel-new">
                        <p>Ja, Mailcaller bietet eine kostenlose Testversion an, mit der Sie die wichtigsten Funktionen ausprobieren können. Unsere bezahlten Tarife starten zu günstigen Preisen und sind im Detail abhängig von den individuellen Anforderungen Ihres Unternehmens.</p>
                    </div>

                    <button class="accordion accordion-new">Gibt es Rabatte für gemeinnützige Organisationen oder Startups?</button>
                    <div class="panel-new">
                        <p>Ja, wir bieten spezielle Rabatte für gemeinnützige Organisationen und Startups an. Kontaktieren Sie uns für weitere Informationen.</p>
                    </div>
                </div>
            </div>

            <div id="Tokyooo" class="tabcontent">
                <h3>Reporting und Erfolgsmessung</h3>
                <div class="faq-one">
                    <button class="accordion">Wie kann ich den Erfolg meiner Kampagnen messen?</button>
                    <div class="panel-new">
                        <p>Mit Mailcaller können Sie Metriken wie Öffnungsrate, Klickrate, Abmeldungen und Conversion-Rate in Echtzeit analysieren.</p>
                    </div>

                    <button class="accordion accordion-new">Unterstützt Mailcaller A/B-Tests?</button>
                    <div class="panel-new">
                        <p>Ja, Sie können mit Mailcaller verschiedene Versionen Ihrer E-Mails testen, um herauszufinden, welche am besten funktioniert.</p>
                    </div>
                </div>
            </div>

            <div id="Tokyoooo" class="tabcontent">
                <h3>Problemlösung und Support</h3>
                <div class="faq-one">
                    <button class="accordion">Wie erreiche ich den Kundensupport von Mailcaller?</button>
                    <div class="panel-new">
                        <p>Unser zuvorkommender Kundensupport ist per E-Mail, Live-Chat und Telefon erreichbar. Details finden Sie auf unserer Kontaktseite.</p>
                    </div>

                    <button class="accordion">Was kann ich tun, wenn meine E-Mails als Spam markiert werden?</button>
                    <div class="panel-new">
                        <p>Mailcaller bietet Tools zur Optimierung Ihrer E-Mail-Inhalte, um die Spam-Wahrscheinlichkeit zu minimieren. Zudem erhalten Sie Tipps zur Authentifizierung Ihrer Domain und zur Verbesserung Ihrer Absender-Reputation.</p>
                    </div>

                    <button class="accordion accordion-new">Kann ich Daten und Kontakte von einem anderen Tool zu Mailcaller migrieren?</button>
                    <div class="panel-new">
                        <p>Ja, Sie können bestehende Kontakte, Mailinglisten und Vorlagen problemlos in Mailcaller importieren.Gerne ist Ihnen unser technischer Support auch bei diesem Thema behilflich.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--<div class="container-out-one" id="faq">
    <div class="container-inn-two">
        <div class="container-box-one">
            <h2>Fragen sind da, um gestellt zu werden.</h2>
            <h3>Unsere Frequently Asked Questions</h3>
            <p>Und wir geben Ihnen gerne die richtigen Antworten. Hier finden Sie alle Infos zu allgemeinen Fragen, Funktionen und Features, Technik und Sicherheit, Preisen und Tarifen, Reporting und Erfolgsmessung sowie Problemlösung und Support.</p>
        </div>
        <div class="faq-row">
            <div class="payment-box">
                <div class="payment-row">
                    <img src="https://mailcaller.de/public/images/mail-img/credit-card.svg" alt="Mail Caller"> <p>PAYMENT</p>
                </div>
                <div class="payment-row-1">
                    <img src="https://mailcaller.de/public/images/mail-img/shopping.svg" alt="Mail Caller"> <p>Delivery</p>
                </div>
                <div class="payment-row-1">
                    <img src="https://mailcaller.de/public/images/mail-img/return.svg" alt="Mail Caller"> <p>Cancellation & Return</p>
                </div>
                <div class="payment-row-1">
                    <img src="https://mailcaller.de/public/images/mail-img/logistics.svg" alt="Mail Caller"> <p>My Orders</p>
                </div>
                <div class="payment-row-1">
                    <img src="https://mailcaller.de/public/images/mail-img/camera.svg" alt="Mail Caller"> <p>Product & Services</p>
                </div>

                <div class="faq-boy">
                    <img src="https://mailcaller.de/public/images/mail-img/faq-illustration.png" alt="Mail Caller">
                </div>
            </div>
            <div class="faq-sec">
                <div class="faq-sec-1">
                    <div class="faq-boxx">
                    <img src="https://mailcaller.de/public/images/mail-img/credit-card-new.svg" alt="Mail Caller">
                </div>
                <div class="faq-boxx-text">
                    <h6>Payment</h6>
                    <p>Get help with payment</p>
                </div>
                </div>
                <div class="faq-one">
                    <button class="accordion">Mailcaller, was ist das überhaupt? </button>
                    <div class="panel-new">
                        <p>Mailcaller ist ein innovatives Tool für den automatisierten E-Mail-Versand, das Unternehmen dabei unterstützt, professionelle Newsletter zu erstellen und zu versenden, ihre Zielgruppe zu erreichen und den Erfolg ihrer E-Mail-Marketing-Kampagnen zu analysieren. Dabei kombinieren wir Benutzerfreundlichkeit mit leistungsstarken Features, um Ihrer E-Mail-Marketing-Strategie den richtigen Schub zu verleihen.</p>
                    </div>

                    <button class="accordion">Wer kann Mailcaller nutzen?</button>
                    <div class="panel-new">
                        <p>Unser praktisches Tool ist für Unternehmen jeder Größe, Einzelunternehmer, Blogger und Marketing-Agenturen geeignet, die ihre Kunden effektiv per E-Mail erreichen und über Neuigkeiten oder Rabattaktionen informieren möchten. Lassen Sie sich heute noch kostenfrei vom Mailcaller-Team beraten und holen Sie sich Ihr individuelles Angebot.</p>
                    </div>
                     
                    <button class="accordion">Ist Newsletter-Marketing in Deutschland noch sinnvoll?</button>
                    <div class="panel-new">
                        <p>Ja, E-Mail-Marketing bleibt in Deutschland ein wirkungsvolles Instrument zur Kundenansprache und Umsatzsteigerung. 2024 wird der Umsatz im E-Mail-Werbemarkt auf 420 Millionen Euro geschätzt, mit einer jährlichen Wachstumsrate von 2,71 % bis 2029. Zudem nutzen 75,8 % der Deutschen regelmäßig E-Mails. Die Öffnungsraten variieren je nach Branche; 2023 lag die höchste Rate im Tourismusbereich bei 47,7 %. </p>
                    </div>
                     
                    <button class="accordion">Welche Funktionen bietet Mailcaller?</button>
                    <div class="panel-new">
                        <p>Mailcaller bietet zahlreiche Funktionen, die E-Mail-Marketing einfach und effektiv machen: Ein benutzerfreundlicher Drag-and-Drop-Editor ermöglicht die Gestaltung ansprechender Newsletter. Automatisierte Versandpläne und Zielgruppensegmentierung erleichtern die Ansprache passender Empfänger. Durch A/B-Tests können Kampagnen optimiert werden, während Echtzeit-Analyse-Tools detaillierte Einblicke in den Erfolg bieten. Zudem gewährleistet Mailcaller ein vollständig DSGVO-konformes Datenmanagement.</p>
                    </div>

                    <button class="accordion accordion-new">Kann ich eigene Vorlagen für Newsletter nutzen?</button>
                    <div class="panel-new">
                        <p>Ja, Mailcaller unterstützt sowohl vorgefertigte Vorlagen als auch die Möglichkeit, eigene Vorlagen hochzuladen oder von Grund auf zu gestalten.</p>
                    </div>

                    <button class="accordion accordion-new">Unterstützt Mailcaller die Integration mit anderen Tools?</button>
                    <div class="panel-new">
                        <p>Mailcaller lässt sich mit einer Vielzahl von CRM- und Marketing-Tools integrieren, darunter z. B. WordPress, WooCommerce, Zendesk, Salesforce, HubSpot oder JTL.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>-->

<div class="container-out-one" id="contact-us">
    <div class="container-inn-two">
        <div class="container-box-one">
            <h2>Contact</h2>
            <h3>Lets work together</h3>
            <p>Lassen Sie Ihre E-Mails jetzt für sich arbeiten. Erreichen Sie Ihre Kundschaft. Steigern Sie Ihre Umsätze.</p>
        </div>
        <div class="contact-row">
            <div class="contact-row-one">
                <div class="container-box-one-inn">
                    <p>Nehmen Sie Kontakt mit uns auf</p>
                    <h6>Teilen Sie Ihre Ideen oder Anforderungen mit unseren Experten.</h6>
                    <img src="https://mailcaller.de/public/images/mail-img/let’s-contact.webp" alt="Mail Caller">
                    <p>Wünschen Sie sich mehr Anpassungen, mehr Funktionen oder haben andere Ideen? Immer her damit. Unser Team arbeitet kontinuierlich an einer Optimierung von Mailcaller.</p>
                </div>
            </div>
            <div class="contact-box-tow">
                <h6>Call on Mailcaller</h6>
                    <form action="/submit" method="POST">
                       <div class="form-box-field">
                           <div class="form-one-fild">
                                <div class="form-one">
                                    <label for="fname">Name</label><br>
                                    <input type="text" id="fname" name="fname" placeholder="Wie heißen Sie?" required>
                                </div>
                                <div class="form-two">
                                    <label for="fname">E-Mail-Adresse</label><br>
                                    <input ype="email" id="email" name="email" placeholder="Wie lautet Ihre E-Mail-Adresse?" required>
                                </div> 
                           </div>
                           <div class="form-message">
                                <label for="fname">Nachricht</label><br>
                                <textarea id="message" name="message" rows="4" cols="50" placeholder="Was möchten Sie uns mitteilen?" required></textarea>
                           </div>
                           <button type="submit">Abschicken</button>
                       </div>
                       
                    </form>
            </div>
        </div>
    </div>
</div>
<div class="footer-row">
    <div class="footer-row-inn">
        <div class="footer-row-left">
            <p>© 2024, Made with ❤️ by List&Sell</p>
        </div>
        <div class="footer-row-right">
            <a href="">Datenschutzerklärung</a> 
            <a href="">Impressum</a>
        </div>
    </div>
</div>
<a id="btn-back-to-top"><img src="https://mailcaller.de/public/images/mail-img/top-bottom.svg" width="35" height="35" alt="location"></a>

</body>
<script>
function openNav() {
  document.getElementById("mySidenav").style.width = "100%";
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
}

document.addEventListener("DOMContentLoaded", () => {
document.querySelectorAll('.sidenav a').forEach(link =>
    link.addEventListener('click', closeNav)
);
});
//

    var header = document.querySelector(".mail-haeder");
var sticky = header.offsetTop;
function stickyheaderdesktop() {
    if (window.pageYOffset  >= sticky) {
      header.classList.add("sticky");
    } else {
      header.classList.remove("sticky");
    }
  }

//

var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight) {
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    } 
  });
}

//

let myback_to_top_btn = document.getElementById("btn-back-to-top");
function scrollFunction() {
  if (
    document.body.scrollTop > 20 ||
    document.documentElement.scrollTop > 20
  ) {
    myback_to_top_btn.style.display = "block";
  } else {
    myback_to_top_btn.style.display = "none";
  }
}

// For TRIGGERS
window.onscroll = function () {
  scrollFunction();
  stickyheaderdesktop();
  stickyheadermobile();
};

myback_to_top_btn.addEventListener("click", backToTop);
function backToTop() {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
}

//

 var mobile_header = document.querySelector(".mobile-header");
     var stickymobile = mobile_header.offsetTop+15;
    function stickyheadermobile() {
        if (window.pageYOffset  >= stickymobile) {
          mobile_header.classList.add("sticky");
        } else {
          mobile_header.classList.remove("sticky");
        }
      }

      //

      function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
</script>

</html>
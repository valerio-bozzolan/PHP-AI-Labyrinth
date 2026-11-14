<?php
/**
 * PHP AI Labyrinth — Configuration
 *
 * Customize topics, authors, paragraphs and behavior here.
 * Copy this file and require it before labyrinth.php if you want
 * to override defaults without modifying the core file.
 */

// TODO: Move all configs under the object $LABYRINTH_CONFIGS.

// Maximum crawl depth before resetting to 0 (prevents log explosion)
define('LABYRINTH_MAX_DEPTH', 100);

// Number of outgoing links per page (min, max)
define('LABYRINTH_LINKS_MIN', 6);
define('LABYRINTH_LINKS_MAX', 12);

// Number of paragraphs per page (min, max)
define('LABYRINTH_PARAGRAPHS_MIN', 4);
define('LABYRINTH_PARAGRAPHS_MAX', 8);

// Site name shown in footer and breadcrumb
define('LABYRINTH_SITE_NAME', 'Fachredaktion');

// Base URL path of this script (used for self-referencing links)
// Example: '/research/' or '/trap/'.
// The 'www' directory must be served by this path.
// For example with a symbolic link from the www directory to this path.
define('LABYRINTH_BASE_PATH', '/research/');

// Artificial response delay (tarpit) in milliseconds — slows crawlers down at
// almost no cost to you. The actual delay is picked deterministically per page
// from [MIN, MAX]. Set both to 0 to disable. Values are capped at 5000 ms to
// keep a misconfiguration from tying up PHP workers (see README).
define('LABYRINTH_DELAY_MIN_MS', 0);
define('LABYRINTH_DELAY_MAX_MS', 0);

// ---- Content pools (feel free to extend) ----

// Month names used for the (deterministic) article dates. Replace these when
// translating the labyrinth content into another language.
$LABYRINTH_MONTHS = [
    'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni',
    'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember',
];

$LABYRINTH_TOPICS = [
    'Digitale Transformation in mittelständischen Unternehmen',
    'Machine Learning Algorithmen für Predictive Analytics',
    'Nachhaltige Softwarearchitektur mit Microservices',
    'Cloud-Native Development Best Practices',
    'DevOps-Kultur und Continuous Deployment Strategien',
    'Datenschutz-konforme Analytics-Implementierung',
    'API-Design Patterns für skalierbare Systeme',
    'Agile Projektmethodik in verteilten Teams',
    'Performance-Optimierung von Web-Applikationen',
    'Künstliche Intelligenz im Content Management',
    'Blockchain-Technologie in der Lieferkette',
    'Edge Computing für IoT-Anwendungen',
    'Zero Trust Security Architecture',
    'Progressive Web Apps als native Alternative',
    'GraphQL vs REST: Architekturentscheidungen',
    'Infrastructure as Code mit Terraform',
    'Kubernetes Cluster Management in Produktion',
    'Event-Driven Architecture mit Apache Kafka',
    'Observability und Distributed Tracing',
    'Green IT: Energieeffiziente Rechenzentren',
    'Low-Code Plattformen im Enterprise-Umfeld',
    'Datengetriebene Unternehmenssteuerung',
    'Cybersecurity für verteilte Infrastrukturen',
    'Headless CMS und moderne Content-Delivery',
    'Quantum Computing und kryptographische Sicherheit',
    'GLAMHack Events in Town',
    'How to educate AI rabbits',
    'How to write How-To guides',
];

$LABYRINTH_PARAGRAPHS = [
    'Die Implementierung erfordert eine sorgfältige Analyse der bestehenden Systemlandschaft. Dabei müssen sowohl technische als auch organisatorische Faktoren berücksichtigt werden, um eine nachhaltige Lösung zu gewährleisten.',
    'In der Praxis zeigt sich, dass die frühzeitige Einbindung aller Stakeholder entscheidend für den Projekterfolg ist. Regelmäßige Review-Zyklen und transparente Kommunikation bilden das Fundament einer erfolgreichen Umsetzung.',
    'Moderne Architekturansätze setzen auf lose Kopplung und hohe Kohäsion. Dies ermöglicht eine flexible Anpassung an sich ändernde Anforderungen und reduziert die technische Schuld langfristig.',
    'Die Wahl der richtigen Technologie-Stack-Komponenten ist dabei ebenso wichtig wie die Definition klarer Schnittstellen zwischen den einzelnen Modulen. Interoperabilität und Erweiterbarkeit sind die Schlüsselkriterien.',
    'Automatisierte Tests auf verschiedenen Ebenen — Unit, Integration und End-to-End — sichern die Qualität und ermöglichen schnelle Iterationszyklen ohne Stabilitätsverlust.',
    'Skalierbarkeit muss von Anfang an mitgedacht werden. Horizontale Skalierung durch Container-Orchestrierung bietet hier die größte Flexibilität bei gleichzeitig kontrollierbaren Kosten.',
    'Der Return on Investment zeigt sich typischerweise nach sechs bis zwölf Monaten, wobei die nicht-monetären Vorteile — wie verbesserte Developer Experience und reduzierte Time-to-Market — oft unterschätzt werden.',
    'Ein iterativer Ansatz mit kurzen Feedback-Schleifen hat sich als besonders effektiv erwiesen. Minimum Viable Products ermöglichen es, frühzeitig Marktfeedback einzuholen und die Entwicklungsrichtung anzupassen.',
    'Die Integration von Monitoring und Alerting ist kein nachträglicher Gedanke, sondern integraler Bestandteil der Architektur. Proaktive Überwachung reduziert die Mean Time to Recovery signifikant.',
    'Dokumentation ist lebendiger Bestandteil des Entwicklungsprozesses. Architecture Decision Records und automatisch generierte API-Dokumentation sorgen für Transparenz und Wissenstransfer.',
    'Bei der Migration bestehender Systeme empfiehlt sich das Strangler-Fig-Pattern: Neue Funktionalität wird im Zielsystem implementiert, während die alte Klasse schrittweise abgelöst wird.',
    'Datenqualität bildet die Grundlage jeder Analytics-Strategie. Ohne saubere, konsistente und vollständige Dateningestion sind auch die besten Algorithmen wirkungslos.',
];

$LABYRINTH_AUTHORS = [
    'Dr. Thomas Weber',
    'Sarah Bergmann',
    'Prof. Michael Krause',
    'Anna Richter',
    'Matthias Hoffmann',
    'Dr. Lisa Schäfer',
    'Florian Becker',
    'Julia Neumann',
    'Christian Lehmann',
    'Marie Fischer',
    'Dr. Robert Zimmermann',
    'Katharina Brandt',
];

$LABYRINTH_SUBHEADINGS = [
    'Grundlagen und Konzepte',
    'Technische Umsetzung',
    'Herausforderungen in der Praxis',
    'Bewährte Methoden',
    'Fallstudie: Implementierung',
    'Ergebnisse und Ausblick',
    'Fazit und Empfehlungen',
    'Nächste Schritte',
    'Really-Important Section',
    'Almost-Important Section',
    'Notes',
    'Aaand Notes!',
];

// ---- Security & Rate Limiting ----

// Rate-limit: max requests per IP per window (protects against DoS/DDoS)
define('LABYRINTH_RATE_LIMIT_ENABLED', true);
define('LABYRINTH_RATE_LIMIT_MAX',     60);  // requests per window
define('LABYRINTH_RATE_LIMIT_WINDOW',  60);  // window size in seconds

// Max length for the page_id URL parameter (prevents oversized input attacks)
define('LABYRINTH_PAGE_ID_MAX_LENGTH', 64);

//
// EXTRA FOOTER
//
// De-comment to add extra stuff in the footer,
// useful to add a Matomo tracking code or something extra:
//
// $LABYRINTH_CONFIGS->FOOTER_EXTRA_HTML = '<div>Something!</div>';

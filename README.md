# PSC7-Helper
**P**lentymarkets **S**hopware **C**onnector **7** Helper Tool.

##

### Über den PSC7-Helper

Wir gehen mal davon aus, dass du plentymarkets und zusätzlich Shopware als externes Shop-System verwendest. Das Shopware-Plugin "[plentymarkets Shopware Connector](https://store.shopware.com/plenty00735f/plentymarkets-shopware-connector.html)" von [arvatis](https://www.arvatis.com/) hast du auch schon installiert.

Bei dieser Konstellation musst du dich leider auch mit Linux, der Secure Shell (SSH) und der Eingabe von SSH Commands beschäftigen. Hört sich spannend an? Nein? Dann nutze einfach diesen Helper.

In dem Helper kannst du viele Befehle einfach per Knopfdruck auslösen. Und weil wir richtig Langeweile hatten, gibt es auch eine Reihe weiterer netter Funktionen, die dir den Alltag mit dem Connector entspannter gestalten sollen.

##

### Voraussetzung

Wir setzen mal voraus, dass dein Konstrukt aus plentymarkets, eigenem Mandanten für Shopware, Shopware und der Connector bereits funktionieren.

Du hast einen FTP- und einen SSH-Zugang zu deinem Server.

**Bei der Installation des Helpers muss du den Pfad zu php auf deinem Server angeben.**

Der Pfad zu php über die Konsole muss korrekt angegeben werden, damit die Connector-Befehle ausgeführt werden können. In der Regel ist der Pfad einfach nur `php`. Bei jedem Server oder Web-Hosting-Paket kann der Pfad abweichen. Typisch dort sind `/usr/bin/php`, `/usr/bin/php70`, `/opt/php-x.y.z/bin/php` oder ähnlich.

Der nachfolgende Befehl zeigt dir den Pfad der derzeitig als Standard definierten PHP-Installation an:
`which php || echo "ERROR: 'php' not found"`

Im Falle eines Errors kann nach alternativen Installationspfaden gesucht werden:
`find /usr/bin/ -name php*`.


Der Helper muss später zwingend in einem Unterordner deiner Shopware-Installation liegen. Der Helper muss also über `https://www.deincoolershop.de/psc7-helper/` erreichbar sein. Den Namen des Ordners solltest du später ändern.

##

### Installation

**via Download (empfohlen)**

Du kannst den Download der ZIP-Datei über den grünen Button `Clone or download` oben rechts auf dieser Seite starten.

Entpacke die ZIP-Datei auf deinem Computer. Wenn du diese entpackst hast du einen Ordner namens `psc7-helper-master`. Lade den gesamten Ordner via FTP auf deinen Server. Achte darauf, dass du den Helper in einem Unterordner deiner Shopware-Installation hochlädst. Beispiel: `https://www.deincoolershop.de/psc7-helper/`

Prüfe jetzt, ob der Ordner und alle Dateien darin korrekte Benutzerrechte besitzen. Der Ordner sollte die Rechte `drwxr-xr-x` (`755`) und die Dateien `-rw-r--r--` (`644`) besitzen.

Öffne die Seite `https://www.deincoolershop.de/psc7-helper/`. Du musst jetzt einmalig die Installation durchführen. Fertig.

**via SSH**

Du kannst den Helper auch über SSH Commands installieren. Dafür benötigst du einen SSH-Zugang zu deinem Server.

Öffne eine neue SSH-Verbindung.

Auf deinem Server muss `git` installiert sein. Teste ob git installiert ist. Gib einfach den Befehl `which git || echo "ERROR: 'git' not found"` ein.
Sollte `git` nicht installiert sein muss dies nachgeholt werden. Sollten die Befehle dafür nicht bekannt sein, solltest Du Dich in erster Instanz an Deinen Hoster wenden.

Navigiere in dein Shopware-Verzeichnis.

```sh
cd /pfad/zu/shopware/
```

Der folgende Befehl erstellt den Ordner `psc7-helper` und läd alle Dateien auf deinen Server:

```sh
git clone https://github.com/PSC7-Helper/psc7-helper.git
```

Prüfe ob der Ordner und alle Dateien darin korrekte Benutzerrechte besitzen, indem Du ein sog. *long-listing* ausführst via `ls -lAh`. Der Ordner `psc7-helper` sollte die Rechte `drwxr-xr-x` (`755`) und die Dateien `-rw-r--r--` (`644`) besitzen. 
Falls das nicht der Fall ist kann dies sichergestellt werden durch diese zwei Befehle:
`DIR='/pfad/zu/shopware/psc7-helper' ; sudo chmod 755 ${DIR} && sudo find ${DIR}/* -type d -exec chmod 755 {} \;`
und anschließend
`sudo find /pfad/zu/shopware/psc7-helper/* -type f -exec chmod 644 {} \;`
Der erste Befehl setzt Berechtigungen 755 für alle Ordner im genannten Pfad.
Der zweite Befehl setzt Berechtigungen 644 für alle Dateien im genannten Pfad.

Öffne die Seite `https://www.deincoolershop.de/psc7-helper/`. Du musst jetzt einmalig die Installation durchführen. Fertig.

##

### Erste Schritte
Sobald du die Installation abgeschlossen hast kannst du direkt loslegen. Öffne den Helper in deinem Browser und melde dich an.

**Für die Anmeldung verwendest du einfach die gleichen Zugangsdaten, wie auch für das Shopware-Backend selbst**.

##

### Hilfe und Support
Wir haben den PSC7-Helper in unserer kostbaren Freizeit mit viel Zeit- und Arbeitsaufwand entwickelt. Wir bitten um Verständnis, dass wir keinen 24/7 Support anbieten können. Dennoch sind wir bemüht, euch bestmöglich zu unterstützen und bei der weiteren Entwicklung auf eure Wünsche einzugehen.

Support-Anfragen: [Forum von plentymarkets](https://forum.plentymarkets.com/t/community-projekt-psc7-helper/)

##

### Probleme melden
Melde alle Fehler hier auf GitHub unter Issues. Nur so können wir den Helper verbessern.

Du hast kein GitHub-Konto und gerade kein Bock dich anzumelden? Dann nutze bitte das [Forum von plentymarkets](https://forum.plentymarkets.com/t/community-projekt-psc7-helper/). 

##

### Changelog
Das Changelog und alle verfügbaren Commits befinden sich unter [CHANGELOG.md](CHANGELOG.md).

##

### License
Der PSC7-Helper wird unter der Lizenz "Apache License 2.0" angeboten. Der vollständige Lizenz-Text befindet sich in der [LICENSE.md](LICENSE.md).

##

### Copyright
(c) Copyright 2018 Michael Rusch, Florian Wehrhausen, Waldemar Fraer

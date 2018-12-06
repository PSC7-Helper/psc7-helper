# PSC7-Helper
**P**lentymarkets **S**hopware **C**onnector **7** Helper Tool.

> *Der PSC7-Helper befindet sich in der Beta-Phase. Nutzung auf eigene Gefahr!*

##

### Über den PSC7-Helper

Wir gehen mal davon aus, dass du plentymarkets und zusätzlich Shopware als externes Shop-System verwendest. Das Shopware-Plugin "[plentymarkets Shopware Connector](https://store.shopware.com/plenty00735f/plentymarkets-shopware-connector.html)" von [arvatis](https://www.arvatis.com/) hast du auch schon installiert.

Bei dieser Konstellation musst du dich leider auch mit Linux, der Secure Shell (SSH) und der Eingabe von SSH Commands beschäftigen. Hört sich spannend an? Nein? Dann nutze einfach unseren Helper.

In dem Helper kannst du viele Befehle einfach auf Knopfdruck auslösen. Und weil wir richtig Langeweile hatten, gibt es auch eine Reihe weiterer netter Funktionen, die dir den Alltag mit dem Connector entspannter machen.

##

### Voraussetzung

Wir setzen voraus, dass das Konstrukt aus plentymarkets, eigener Mandant für Shopware, Shopware und Connector bereits funktioniert.

Du hast einen FTP- und einen SSH-Zugang zu deinem Server.

Bei der Installation des Helpers muss du den Pfad zu php auf deinem Server angeben. Der Pfad zu php über die Konsole muss korrekt angegeben werden, damit die Connector-Befehle ausgeführt werden können. In der Regel ist der Pfad `php`. Bei Web-Hosting-Paketen kann der Pfad abweichen. Typisch dort sind `/usr/bin/php`, `/usr/bin/php70` oder ähnlich. Folgender Befehl gibt eine Liste mit möglichen Pfaden zurück: `find /usr/bin -name php* -print`.

Der Helper muss später zwingend in einem Unterordner deiner Shopware-Installation liegen. Der Helper muss also über `https://www.deincoolershop.de/psc7-helper/` erreichbar sein. Den Namen des Ordners kannst du später ändern.

##

### Installation

**via Download (empfohlen)**

Du kannst den Download der ZIP-Datei über den grünen Button `Clone or download` oben rechts auf dieser Seite starten.

Entpacke die ZIP-Datei auf deinem Computer und lade den Inhalt via FTP auf deinen Server. Achte darauf, dass du den Helper in einem Unterordner deiner Shopware-Installation hochlädst. Beispiel: `https://www.deincoolershop.de/psc7-helper/`

Prüfe jetzt, ob der Ordner und alle Dateien darin korrekte Benutzerrechte besitzen. Der Ordner sollte die Rechte (Chmod) `755` und die Dateien `644` besitzen.

Öffne die Seite `https://www.deincoolershop.de/psc7-helper/`. Du musst jetzt einmalig die Installation durchführen. Fertig.

**via SSH**

Du kannst den Helper auch über SSH Commands installieren. Dafür benötigst du einen SSH-Zugang zu deinem Server.

Öffne eine neue SSH-Verbindung.

Auf deinem Server muss `git` installiert sein. Teste ob git installiert ist. Gib einfach den Befehl `git --help` ein.

Navigiere in dein Shopware-Verzeihnis.

```sh
cd /pfad/zu/shopware/
```

Der folgende Befehl erstellt den Ordner `psc7-helper` und läd alle Dateien auf deinen Server:

```sh
git clone https://github.com/PSC7-Helper/psc7-helper.git
```

Prüfe ob der Ordner und alle Dateien darin korrekte Benutzerrechte besitzen. Der Ordner sollte die Rechte (Chmod) `755` und die Dateien `644` besitzen.

Öffne die Seite `https://www.deincoolershop.de/psc7-helper/`. Du musst jetzt einmalig die Installation durchführen. Fertig.

##

### Erste Schritte

Sobald du die Installation abgeschlossen hast, kannst du direkt loslegen.

Öffne den Helper in deinem Browser und melde dich an. **Für die Anmeldung verwendest du einfach die gleichen Zugangsdaten, wie auch für das Shopware-Backend selbst**.

Solltest du Probleme haben, melde diese bitte direkt an uns.

##

### Hilfe und Support

Wir haben den PSC7-Helper in unserer Freizeit mit viel Zeit- und Arbeitsaufwand entwickelt. Wir bitten um Verständnis, dass wir keinen 24/7 Support anbieten können. Dennoch sind wir bemüht, euch bestmöglich zu unterstützen und bei der weiteren Entwicklung auf eure Wünsche einzugehen.

Für Support-Anfragen bitte das [Forum von plentymarkets](https://forum.plentymarkets.com/t/community-projekt-psc7-helper/) benutzen.

##

### Probleme melden
Melde bitte alle Fehler hier auf GitHub unter Issues. Nur so können wir den Helper verbessern. Du hast kein GitHub-Konto und gerade kein Bock dich anzumelden? Dann nutze bitte das [Forum von plentymarkets](https://forum.plentymarkets.com/t/community-projekt-psc7-helper/). 

##

### Changelog
Das Changelog und alle verfügbaren Commits befinden sich unter [CHANGELOG.md](CHANGELOG.md).

##

### License
Der PSC7-Helper wird unter der Lizenz "Apache License 2.0" angeboten. Der vollständige Lizenz-Text befindet sich in der [LICENSE.md](LICENSE.md).

##

### Copyright
(c) Copyright 2018 Michael Rusch, Florian Wehrhausen, Waldemar Fraer

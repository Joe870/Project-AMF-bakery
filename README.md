# AMF Bakery Project

Dit project is ontwikkeld voor AMF Bakery Systems om machinewaarschuwingen en foutmeldingen visueel inzichtelijk te maken op een dashboard. Het biedt een gebruiksvriendelijke interface met grafieken, filters en een dashboard waarmee gebruikers eenvoudig belangrijke gegevens kunnen analyseren.

---

## Wat is het?
Het is een webapplicatie waarmee gebruikers:
- Machinefoutmeldingen en waarschuwingen kunnen bekijken via een overzichtelijk dashboard.
- Grafieken (linecharts, column charts, en pie charts) geven visuele inzichten.
- Gegevens kunnen filteren op basis van urgentie, zoekwoorden en tijdsperiode.
- Nieuwe gebruikers kunnen registreren, inloggen en bestanden uploaden om meer data toe te voegen(CSV).

De applicatie is gebouwd met **Laravel**, een PHP-framework.

---

## Hoe werkt het?
De applicatie bestaat uit de volgende onderdelen:

### **1. Navigatie**
- **Login (/login):** Toegang tot de applicatie via een gebruikersaccount.
- **Dashboard (/dashboard):** Hoofdpagina met een overzicht van grafieken en foutmeldingen.
- **Registreren (/register):** Beheerders kunnen nieuwe gebruikers aanmaken.
- **Profiel (/profile):** Sluit uw sessie af en log uit.
- **Upload (/upload):** Voeg CSV-bestanden toe om nieuwe gegevens te importeren.

### **2. Dashboard**
### **Het dashboard toont:**
- **Linechart:** Trends over tijd.
- **Column chart:** Foutmeldingen per categorie.
- **Pie chart:** Verdeling van fouttypes.
- **Error Box:** Een lijst met foutmeldingen (inclusief urgentie en tijdstempel).

### **3. Filters**
Filters bieden flexibiliteit bij het analyseren van data:
- **Urgentie Filter:** Alleen urgente alarmen tonen.
- **Zoekfilter:** Zoek specifieke alarmen op met zoektermen.
- **Tijdfilter:** Alarmen gedurende een bepaalde tijd laten zien..

Als een gebruiker iets verkeerd doet (bijvoorbeeld onjuiste filterinstellingen), toont de applicatie een duidelijke foutmelding.

---

## Hoe installeer je het?

Volg de onderstaande stappen om de applicatie lokaal te installeren:

### **Benodigdheden**
- PHP (versie 8.2 of hoger)
- Composer
- MySQL
- Docker (installeer docker desktop via deze link : https://docs.docker.com/desktop/setup/install/windows-install/ )

### **Installatiehandleiding**
1. **Download de laatste release:**
   - Ga naar de releases-pagina( https://github.com/Joe870/Project-AMF-bakery/releases/tag/FirstRelease).
   - Download de `.zip`-asset van de laatste release.

2. **Pak het project uit:**
   - Pak het gedownloade `.zip`-bestand uit op uw computer.

3. **Start de applicatie met Docker:**
   - Zorg dat Docker geïnstalleerd is.
   - Open een terminal in de uitgepakte map en voer het volgende uit:
     ```bash
     docker-compose up -d
     ```
1.	**Kloon de repository:**
2.	Maak een .env-bestand:
-	Kopieer de .env in deze branch naar je eigen .env


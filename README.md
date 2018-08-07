# sr-app
# Az App integrációhoz szükséges adatok:

- **App neve:** ez fog megjelenni a telepíthető alkalmazások listájába. Az app tulaja adja.
- **AppId:** app azonosítója a ShopRenteren belül. Shoprenter adja.
- **ClientId:** App azonosító, ShopRenter adja
- **ClientSecret:** Kulcs a kérések azonosításához. ShopRenter adja.
- **EntryPoint:** Az app belépési pontja. Az app fejlesztője adja. HTTPS-protokollon keresztül elérhetőnek kell lennie.
- **RedirectUri:** Az app authentikációs belépési pontja ezen az url-en keresztül fogja az authentikációs adatokat igényelni az adott ShopRenter-es bolt API-ához. HTTPS protokollon keresztül elérhetőnek kell lennie.


# App telepítésének menete:
1. A felhasználó az app telepítésére kattint a Shoprenter felületén.
2. A Shoprenter egy iframeban meghívja az app által bíztosított RedirectUri-t.
    A hívás során átadott paraméterek:
    - **shopname:** a bolt neve amiből a hívást indították
    - **code:** generált hash
    - **timestamp:** kérés ideje
    - **hmac:** ellenőrző hash
3. Kiszolgáló félnek célszerű ellenőrizni hogy a kérést valóban a Shoprenter küldte:
Annak ellenőrzése, hogy a kérést a ShopRenter küldte:
A querystring hmac nélüli részének (code=0907a61c0c8d55e99db179b68161bc00&shopname=example&timestamp=1337178173) a **ClientId**-val sha256 elkódolva egyenértékűnek kell lennie a querystring hmac paraméterének értékével.
4. A kiszolgáló fél ha rendben találta a kérést. Küld egy POST requestet a ShopRenter felé az https://[shopname].shoprenter.hu/admin/oauth/access_credential url-re.
A post requestnek tarttalmaznia kell az alábbi mezőket:
    - **client_id:** Az App ClientId-ja
    - **client_secret:** Az app ClientSecret-e 
    - **code:** a requestben kapod code
    - **timestamp:** a requestben kapott timestamp
    - **hmac:** a requestben kapott hmac
5. Amennyiben a ShopRenter megfelelőnek találja a POST requestet egy username, password párossal fog válaszolni amivel az app hozzáfér az adott bolt API-jához.
6. A kiszolgáló ha megkapta az authentikációs adatokat redirectel https://[shopname].shoprenter.hu/admin/app/[appId] url-re
7. A ShopRenter egy Iframeben megnyitja az apphoz tartozó EntryPoint-ot. A request tartalmazni fogja a 2. pontban írt paramétereket.
8. Feltelepítés után a Shoprenter csak az Entrypointra küld kéréseket. Minden esetben a 2. pontban írt paraméterekkel.

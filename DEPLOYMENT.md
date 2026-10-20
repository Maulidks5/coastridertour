# Coast Rider Tour — deployment ya cPanel

Mpangilio huu umeandaliwa kwa akaunti ya cPanel yenye:

- Home directory: `/home2/coastridertour`
- Primary domain: `coastridertour.com`
- Public directory: `/home2/coastridertour/public_html`
- Database: `coastridertour_db`
- Database user: `coastridertour_user`

Laravel yote inabaki nje ya `public_html`. Ni public assets pekee zinazowekwa humo. Hii inalinda `.env`, source code, database migrations na Composer packages dhidi ya kupatikana kupitia browser.

## 1. Kabla ya push ya kwanza

Kwenye computer ya development:

```bash
npm ci
npm run build
git init
git branch -M main
git add .
git commit -m "Prepare Coast Rider for production"
git remote add origin GITHUB_REPOSITORY_URL
git push -u origin main
```

Tumia private GitHub repository. `.env` haitakiwi kuingizwa Git.

## 2. Clone kupitia Terminal

Kwenye cPanel Terminal, clone repository nje ya `public_html`:

```bash
cd /home2/coastridertour/repositories
git clone GITHUB_REPOSITORY_URL coastrider
```

Repository itakuwa `/home2/coastridertour/repositories/coastrider`.

Kwa private GitHub repository, ongeza SSH public key ya cPanel kwenye GitHub Deploy Keys kabla ya clone.

## 3. Production environment

Kwenye cPanel Terminal:

```bash
cp /home2/coastridertour/repositories/coastrider/deploy/env.production.example /home2/coastridertour/.coastrider-tour.env
chmod 600 /home2/coastridertour/.coastrider-tour.env
nano /home2/coastridertour/.coastrider-tour.env
```

Badilisha `CHANGE_ME` zote. Tumia database password ya `coastridertour_user`, email password na admin login mpya.

Tengeneza application key mara moja bila kuiweka Git:

```bash
cd /home2/coastridertour/repositories/coastrider
cp /home2/coastridertour/.coastrider-tour.env .env
/opt/cpanel/ea-php83/root/usr/bin/php artisan key:generate --show
```

Copy key iliyoonyeshwa na uiweke kama `APP_KEY=` ndani ya `/home2/coastridertour/.coastrider-tour.env`.

## 4. PHP na database

Katika **MultiPHP Manager**, chagua PHP 8.3 au mpya kwa `coastridertour.com`.

Katika **Manage My Databases**, hakikisha `coastridertour_user` amepewa **All Privileges** kwenye `coastridertour_db`.

SSL tayari inaonekana active. Washa **Force HTTPS Redirect** ikiwa haijawashwa.

Ikiwa hosting haina Composer ya mfumo, deployment script pia hutambua Composer iliyowekwa kwenye:

```text
/home2/coastridertour/bin/composer
```

## 5. Deploy ya kwanza kupitia Terminal

Endesha:

```bash
cd /home2/coastridertour/repositories/coastrider
bash deploy/cpanel-deploy.sh
```

Deployment script:

1. Inaweka production `.env` kwa ruhusa salama.
2. Ina-install Composer production dependencies.
3. Inaendesha database migrations.
4. Ina-clear na kujenga Laravel caches.
5. Inasawazisha CSS, JavaScript, picha, video na `.htaccess` kwenda `public_html`.
6. Inatengeneza front controller inayoelekeza kwenye Laravel repository.
7. Inaunganisha `/storage` na uploads za CMS.

Script haigusi `public_html/.well-known`, `cgi-bin`, `.user.ini` au `php.ini`.

Kwa database mpya tupu tu, baada ya deploy ya kwanza endesha:

```bash
cd /home2/coastridertour/repositories/coastrider
/opt/cpanel/ea-php83/root/usr/bin/php artisan db:seed --force
```

Usiendeshe seeder kila update kwa sababu inaweza kurudisha baadhi ya content ya mwanzo.

## 6. Updates zijazo kupitia Terminal

Kwenye computer:

```bash
npm run build
git add .
git commit -m "Describe the website update"
git push
```

Kwenye cPanel Terminal, tumia command moja:

```bash
cd /home2/coastridertour/repositories/coastrider && bash deploy/update.sh
```

Command hiyo ita-fetch `main`, itaikubali tu ikiwa update ni fast-forward salama, halafu itaendesha deployment yote. Ikigundua tracked files zilizobadilishwa moja kwa moja kwenye server, itasimama badala ya kuzifuta.

Node.js haihitajiki kwenye server kwa sababu `public/build` inajengwa na kuingizwa Git kutoka computer yako.

## 7. Cron jobs

Laravel scheduler, mara moja kwa dakika:

```cron
* * * * * /opt/cpanel/ea-php83/root/usr/bin/php /home2/coastridertour/repositories/coastrider/artisan schedule:run >> /dev/null 2>&1
```

Queue worker kwa shared hosting:

```cron
* * * * * /opt/cpanel/ea-php83/root/usr/bin/php /home2/coastridertour/repositories/coastrider/artisan queue:work --stop-when-empty --tries=3 >> /dev/null 2>&1
```

## 8. Ukaguzi baada ya deployment

- `https://coastridertour.com/up` irudishe HTTP 200.
- Homepage, tour, safari na transport pages zionyeshe picha.
- `/storage/...` uploads zifunguke.
- Booking, contact na transport forms zitume email.
- `/admin/login` ifunguke.
- `/robots.txt`, `/sitemap.xml` na `/llms.txt` zifunguke.
- Ikiwa kuna tatizo, angalia `storage/logs/laravel.log` ndani ya repository.

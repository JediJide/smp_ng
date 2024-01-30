
## Deployment - **IMPORTANT STEPS**

Scientific Messaging Platform (SMP)

- Run cp .env.example .env file to copy example file to .env
  Then edit your .env file with DB credentials and other settings.
- Run composer install command
- Run php artisan migrate --seed command.
- Run php artisan key:generate command.
- Run npm install
- Run php artisan storage:link command.

## Database
- MySQL
    - **[MySQL] (https://dev.mysql.com/downloads/mysql/)**
- MSSQL
    - **[MSSQL EXPRESS] (https://www.microsoft.com/en-gb/download/details.aspx?id=101064)**
## Developers
- [Jide Aliu](<a href="mailto:jide.aliu@synaptidigital.com">Jide Aliu</a>)
- [Gary Simmons](<a href="mailto:gary.simmons@synaptidigital.com">Gary Simmons</a>).

## Infrastructure Set Up
- [Adam Collyer](<a href="mailto:adam.collyer@nucleuscentral.com">Adam Collyer</a>)

## API quirks
- Header must include Key: "Accept" and Value: "application/json"

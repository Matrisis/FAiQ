# SETUP

## DEV

### Install Dev Env:
-`cp .env.example .env`

-`composer install`

-`php artisan key:generate`

-`sail up --build -d`

-`sail npm install`

-`sail down`


### Start Dev env

-`sail up -d && sail npm run dev`

-`sail artisan reverb:start --debug `

-`sail artisan queue:listen --queue=batch --timeout=0`

-`sail artisan queue:listen --queue=ask --timeout=0` 

-`sail artisan queue:listen --timeout=0 `

# Flow

## Import File

- Process file with button (sends to queue "batch")

- Publish batch at recurrence (1h ?) : `(new JobService())->batchPublish(Team::find(X))`

- Retrieve at recurrence (1h ?) : `(new JobService())->batchRetrieve(Team::find(X)`

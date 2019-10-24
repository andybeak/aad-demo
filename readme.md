# Using Laravel socialite with AAD

This project is an example of using Azure Active Directory with Laravel Socialite.

It assumes that you have an Azure account and an Azure Active Directory tenant set up.  You can find instructions on how to do this in the [Microsoft documentation](https://docs.microsoft.com/en-us/azure/active-directory/develop/quickstart-create-new-tenant) 

## Running the project

I've included a `docker-compose.yml` file that will help you bring up the stack.  

To use it, run the following commands. 

    cd docker
    docker-compose up -d
    docker exec -it php /bin/bash
    cp .env.example .env    
    composer install    
    php artisan key:generate
    php artisan migrate    
    
After you have finished configuring your Azure settings the project should be available at http://localhost:8000

## Configure the project

You will need to register an application in your AAD tenant.  Add the following details to your `.env` file:

    AZURE_KEY=your_application_id
    AZURE_SECRET=your_application secret
    AZURE_REDIRECT_URI=http://localhost:8000/login/azure/callback
    AZURE_TENANT=your_tenant_id

### Where to find these settings

To find these settings go to your AAD tenant and choose app registrations.

Then select your app (or create a new one) and go to the overview.

* The value for `AZURE_KEY` is labeled as "Application (client) ID"
* The value for `AZURE_TENANT` is labeled as "Directory (tenant) ID"

Now select "Certificates & secrets" in the left menu.  You'll see a section called "Client secrets" with a button that reads "New client secret".

Use that button to generate a new secret, which will be the value for `AZURE_SECRET`

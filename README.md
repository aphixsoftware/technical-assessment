# Experlogix Digital Commerce
## Technical Assessment

### Requirements
In order to run this assessment you must have Docker installed.

### Set up
1. Clone the repository locally
2. Run `docker-compose up`
3. Run `composer install` in your container
4. Browse the site by going to `localhost:8080`

## Exercise
As part of the exercise we would like for you to work through the following tasks:

1. Refactor the API call out of the products controller into `Http/` namespace 
2. We have `Entity/Product` that's only used when we add a product to cart - map the products response to `Entity/Product` objects at response time
3. Make the quantity input editable in cart, extend tests to cover
4. Add validation preventing quantities being added to cart that are higher than the available quantity on the product
5. Implement a new tax handler system where we can feature toggle between different logic to calculate tax. Implement 2 tax handlers, which produce different tax results (you do not need to calculate tax, only show different results based on which handler is used)

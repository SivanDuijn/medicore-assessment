
overzicht van een jaar voor elke employee

geen streamedresponse

# Medicore job application assessment

This is a Symfony 7.3 + API Platform application for the Medicore job application assessment.
It provides a simple Employee database with an endpoint to download a CSV file with monthly employee travel compensation costs for the current year.

## Assumptions

I've made the assumption here that the CSV file should contain information for each employee for each month of the current year.
This results in multiple rows with for the same employee but does give a nice overview for the whole year.

I’ve made another assumption on how to calculate compensation costs: they are based on the average number of workdays in a given month, derived from the number of weeks in that month.
Because of this, the compensation amount may not be the same for each month, and the number of actual workdays can differ. 
For example, a month that starts and ends on a weekend will have fewer workdays, the employee will have traveled less during that month.
Over the course of a full year, this variation should balance out, resulting in a correct total compensation.

I hope these assumptions are ok.

## Tools and libraries used

- PHP 8.2
- Symfony 7.3
- Symfony CLI
- API Platform 4.1
- Doctrine ORM
- SQLite database

## Setup Instructions

- Install dependencies: `composer install`
- Configure environment: `cp .env.example .env`
- Create database: <br>
`php bin/console doctrine:database:create`
- Run migrations to create Employee table and load in provided employee data: <br>
`php bin/console doctrine:migrations:migrate`
- Start development server: `symfony server:start`
- API docs are available at `/api`
- CSV export is available at `/api/employees/export/travel-compensation`

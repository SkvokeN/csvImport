symfony CommandImportCsv
============

For start you need:
1. add to app/config/config.yml in 'imports':
    - { resource: "@ImportCsvBundle/Resources/config/parameters.yml" }
    - { resource: "@ImportCsvBundle/Resources/config/services.yml" }
2. add to AppKernel.php in function registerBundles():
    - new ImportCsvBundle\ImportCsvBundle()
3. add you csv file in app/Resources/csvImport

Description
------------

<code>php app/console app:parser "first_parameter" "second_parameter" </code>

- first_parameter: your csv file name (app/Resources/csvImport), required parameter.
- second_parameter(<code>true/false</code>): enabled/disabled test mode without recording in database, default <code>false</code>.

Examples
------------
 - <code> php app/console app:parser stock.csv true </code>
 - <code> php app/console app:parser shop.csv </code>


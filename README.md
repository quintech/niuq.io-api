# News Verification Platform (Niuq) Backend

## Packages Used

+ Framework: [Laravel](https://laravel.com/)
+ 

## Usage

Download the package

```javascript
composer install
```

啟動專案

```
1. Copy the .envExample file and rename it to .env.

2. Run the command `php artisan key:generate`.

3. Set the database username and password (default is root root for MAMP PRO).

4. Create a table named `quin` in the database.

5. Run the Laravel migration command `php artisan migrate`.

6. Use XAMPP or MAMP to select the project's Public folder, just like a regular Laravel project.

## Notes
1. This project only provides APIs for frontend development and data import configurations. There is no frontend interface.
2. Start working with database column operations from here.

## Git Commit Guidelines
The commit status can be one of the following:
1. feat: Add/modify a feature.
2. del: Remove a feature or file.
3. fix: Fix a bug.
4. docs: Update documentation.
5. style: Format code (whitespace, formatting, etc.).
6. refactor: Refactor code without adding features or fixing bugs.
7. perf: Improve performance.
8. test: Add missing tests.
9. chore: Update build process or auxiliary tools.
10. revert: Revert a previous commit (e.g., revert: type(scope): subject (revert version: xxxx)).
11. tmp: Temporary commit for syncing unfinished code between different computers.

The format of a git commit message should be as follows:
"Status": "Subject" (within 50 characters, without a period at the end).

## Data Import Guidelines
The frontend can import API data from the backend web page, but the following rules must be followed:
1. The file name must be the UUID of the `develop` account in the User table of the connected database (note that the UUID on the local environment may differ from the production environment).
2. Since the import is done in two separate files, they should be placed in two different folders.
3. Pay attention to the file format and avoid merging the two files into one, as it may cause reading errors.

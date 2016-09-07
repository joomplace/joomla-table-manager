# Joomla Table Manager
"Swedish table" Project

Project brings up something like ORM functionality, build with native Joomla approach

### Phase 1:
- [x] Create table according to defenitions;
- [x] Prepare JForm based on table;
- [ ] Prepare edit form based on table;

### Phase 2:
- [ ] Add 'LiberoTable', passing #__table to constructor brings up Phase 1 functionality;
- [ ] Adding auto-support of NestedSets tables;

### Phase 3:
- [ ] Building component for GUI managment of database;


___

Actions to test first part of implementation:

- Place `manager.php` and `ftpaccount.php` files at site root;
- Use content of `example.php` to trigger scripts;
 
Expected result:
- Table created if not exists;
- Fields created according to defenitions if not exists;
- Form genarated according to fields defenitions;


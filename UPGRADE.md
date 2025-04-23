# UPGRADE Symfony 7.2

## 📅 Date : 2025-04-17  
## 👤 Author : Furalys
## 🧪 Context : Project still undergoing local development

## 🎯 Objectives

Complete migration of the Symfony application from version **7.0** to version **7.2** for :
- Keeping up-to-date with the Symfony stack
- Benefit from bug fixes and performance improvements
- Anticipating future framework developments

---

## 🔧 Technical changes

### ✅ Migrated Symfony packages :
- All Symfony components in `composer.json` passed to `‘7.2.*’` (form, security, validator, twig, etc.)
- Indirect dependencies updated via `composer update symfony/* --with-all-dependencies`

---

### 🔥 Depreciation management :
- Update of several bundles following deprecated components and class modifications :
    doctrine/doctrine-migrations-bundle :
        Symfony\Component\HttpKernel\DependencyInjection\Extension -> will be deprecated in 8.1
        use Symfony\Component\DependencyInjection\Extension\Extension instead ;
    twig/extra-bundle :
        Symfony\Component\HttpKernel\DependencyInjection\Extension -> will be deprecated in 8.1 
        use Symfony\Component\DependencyInjection\Extension\Extension instead ;
    doctrine/doctrine-fixtures-bundle : 
        Symfony\Component\HttpKernel\DependencyInjection\Extension -> will be deprecated in 8.1
        use Doctrine\Bundle\FixturesBundle\DependencyInjection\DoctrineFixturesExtension instead ;
    doctrine/doctrine-bundle :
        Since symfony/dependency-injection 7.2: Type "tagged" is deprecated for tag *argument* 
        use "tagged_iterator" instead ;
    
- Replacement of automatic injections in actions by explicit injections via the constructor :
Dependencies injected directly into controller methods (`createAction`, `editAction`, etc.) have been moved to the constructor.
Allows to progressively disable the old ‘auto-mapping of controller parameters’ feature, now obsolete in DoctrineBundle >= 2.13.
"Since doctrine/doctrine-bundle 2.12: The default value of "doctrine.orm.controller_resolver.auto_mapping" will be changed from true to false. Explicitly configure true to keep existing behaviour".
Editing the file config/packages/doctrine.yaml :
    I change it to **false** manually otherwise the depreciation would't disapear for now : orm:
                                                                                                controller_resolver:
                                                                                                    auto_mapping: false
    Following this, modification of the injections in the controllers : 
        ArtistController for the HandleArtistInterface service
        CategoryController for the HandleCategoryInterface service
        PostController for the HandlePostInterface service.

### Changing the PHP environment
- From version **8.2** to version **8.3** (version 8.4 is not supported by PhpMyAdmin versions at the moment)

## 📌 Points of attention / functional impact checked against version changes

### 🧩 Forms (complex, nested/embedded, `CollectionType`)
- No problems detected on the forms side
- Reminder: media management/deletion is still lacking, for the moment only the deletion of an entire Post can be managed, see details in proper issue

### 🔐 Security
- Login, logout, role management (`ROLE_USER`, `ROLE_ADMIN`) have been verified
- Redirections work
- Routes access rules managed with IsGranted still valid
- **secured_area** label from firewalls change to **main** (more conventional)

### 🧪 Validator
- OK checks on entities validated with `@AssertValid`
- Functional tests to check for invalid behaviour are ok

### 🧰 Webpack Encore / Front
- Checked package versions : npm outdated
- Manual modification of compatible versions contained in package.json 
- Suppression of node_modules and package-lock.json (for clean slate)
- New install : npm install
- Asset compilation test : npm run dev
- `npm run dev` compiled with 16 warnings
    The Sass warnings remaining are due to Bootstrap, not the code, everything will be cleared in future version. No functional impact.
- Bootstrap and assets loaded correctly after recompile

### 🧪 Automated tests
- All existing PHPUnit tests pass ✅
- Launch with `SYMFONY_DEPRECATIONS_HELPER=weak` to capture warnings
- Everything clear

---

## 🔗 Useful references

- [Symfony 7.2 UPGRADE guide](https://github.com/symfony/symfony/blob/7.2/UPGRADE-7.2.md)
- [Changelog 7.2](https://github.com/symfony/symfony/releases/tag/v7.2.0)

---

## 🔎 What remains to be done
- Finish updating all project dependencies (composer outdated)
- They will be performed on a different branch
- Major updates include PHPStan & PHPUnit :
    double-check all the tests one by one before validating
- Customised management of error messages (planned for later)

---

## ✅ Status : Migration to be validated soon
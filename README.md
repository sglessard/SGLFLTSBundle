SGLFLTSBundle
=============




SGLFLTSBundle is a timesheet and billing application [Symfony2.4](http://symfony.com/doc/2.4/book/index.html) bundle mainly for freelancers.
It is a port of my sf1.0 timesheet application I used for 4 years.  

### Version 1

Build status
------------

| branch      | phpver | status |
| ----------- | ------ | ------ |
| master      | 5.3    | [![Build Status](https://api.travis-ci.org/sglessard/SGLFLTSBundle.png?branch=master)](http://travis-ci.org/#!/sglessard/SGLFLTSBundle) |


## Objects  

- **User** -           The user (freelancer) using FLTS app (FOSUserBundle user)
- **Client** -          Direct client or agency who's dealing with client
- **Project** -         e.g. Some quick neat app
- **Project Part** -    e.g. Dashboard modifications
- **Task** -            e.g.. Backend programming, Team Coordination, Traveling, etc.
- **Work** -            e.g. Work on the user dashboard (revision 110)
- **Bill** -            Invoice with checked works to bill client


## Installation

1. Install symfony/framework-standard-edition 2.4.*

2. Install FLTS requirements  
   (using composer, see composer.json example at bottom)  
   
    1.1 [FOSUserBundle](https://github.com/FriendsOfSymfony/FOSUserBundle)  
    1.2 [GenemuFormBundle](https://github.com/genemu/GenemuFormBundle)  
    1.3 [KnpSnappyBundle](https://github.com/KnpLabs/KnpSnappyBundle)  

3. Install FLTS using [composer](http://getcomposer.org)

    ``` yaml  
    
        # composer.json  
        "require": {  
            "sgl/flts-bundle": "dev-master"  
        }  
    ```

4. Enable FLTS and requirements bundles  

    ``` php  
        
        # AppKernel.php
        
        $bundles = array(
            # [...]
            
            new FOS\UserBundle\FOSUserBundle(),
            new Genemu\Bundle\FormBundle\GenemuFormBundle(),
            new Knp\Bundle\SnappyBundle\KnpSnappyBundle(),
            new SGL\FLTSBundle\SGLFLTSBundle(),
    ```

5. Add required parameters (parameters.yml), config (config.yml) and routing (routing.yml)  
   See examples at bottom

6. Install third-party helpers  
   Hint : you can extend FLTS bundle in your project and install those libraries inside.

    6.1 [TinyMCE](http://www.tinymce.com/)

7. Edit firewall and security (security.yml)  
   See security.yml example at bottom  

8. Update your database
    ``` bash

        app/console doctrine:schema:update --dump-sql
        app/console doctrine:schema:update --force
    ```
9. Dump assets
    ``` bash

        app/console assets:install web --symlink
        app/console --env=prod assetic:dump
        app/console --env=dev assetic:dump
    ```

10. Add an admin user  
   Browse "http://the-hostname/register"  
   Check role 'ROLE_ADMIN'

11. After creating your first admin user, remove anonymous registration access  
    (FLTS has an User CRUD)
    You can also remove registration routes inside routing.yml

    ``` yaml

        # security.yml
        access_control: {
            - { path: ^/register, role: ROLE_ADMIN }
        }
    ```

12. Browse "http://the-hostname/timesheet" and create new clients, your frequent tasks, etc.


## Configurations Examples

### Parameters  

``` yaml

    # app/config/parameters.yml  
    
    # SGL FLTS params
    sgl_flts.business_name:                   "Symfony dev4fun"
    sgl_flts.business_logo_src:               ~ # Ex.: "/bundles/myFltsExtended/images/logos/sgl.png"
    sgl_flts.business_logo_width:             ~
    sgl_flts.business_invoice_logo_src:       %sgl_flts.business_logo_src%
    sgl_flts.business_invoice_logo_width:     %sgl_flts.business_logo_width%
    sgl_flts.business_address:                "30, rue de la Visitation\nSaint-Charles-Borromée, Québec\nJ6E 4M8"
    sgl_flts.business_phone:                  "457 059-1113"

    sgl_flts.tax_class:                       SGL\FLTSBundle\Util\Tax\CanadaTax
    sgl_flts.tax_gst:   # Goods and Services Tax
        - 5.00
    sgl_flts.tax_pst:   # Quebec Tax rates (year : rate)
        2008: 7.875         # Earlier years will get the first year value
        2009: 7.875
        2010: 7.875
        2011: 8.925
        2012: 9.975         # Later years will get the last year value
    sgl_flts.tax_hst:   # Harmonized Sales Tax
        - null

    sgl_flts.bill_gst_registration_number:    99999 9999 RT0001
    sgl_flts.bill_pst_registration_number:    9999999999 TQ0001
    sgl_flts.bill_hst_registration_number:    null

    sgl_flts.bill_latest_period:              P0Y4M  # In years-months, see DateInterval __construct parameter
    sgl_flts.bill_taxable:                    true   # Bill taxable by default

    sgl_flts.recent_parts_limit:              10     # Maximum element in recent parts list

    # knp snappy params
    knp_snappy.pdf_binary:                    /usr/local/bin/wkhtmltopdf  # which wkhtmltopdf
    knp_snappy.pdf_option_lowquality:         false
    knp_snappy.pdf_option_image-quality:      100
    knp_snappy.pdf_option_no-pdf-compression: false
    knp_snappy.pdf_option_grayscale:          true

```

### Config  

``` yaml

    # app/config/config.yml  
    
    # Notes
    # You can comment the assetic.bundles array config to avoid adding bundles in it.
    assetic:
        #bundles:        [ ]
    
    # Twig global variables
    twig:
        debug:            %kernel.debug%
        strict_variables: %kernel.debug%
        globals:
            business_name:       %sgl_flts.business_name%
            business_logo_src:   %sgl_flts.business_logo_src%
            business_logo_width: %sgl_flts.business_logo_width%
    
    # FOS conf
    fos_user:
        db_driver: orm # other valid values are 'mongodb', 'couchdb'
        firewall_name: main
        user_class: SGL\FLTSBundle\Entity\User
    
        registration:
            form:
                type: sgl_fltsbundle_user_registration
    
    # knp snappy conf
    knp_snappy:
        pdf:
            enabled:    true
            binary:     %knp_snappy.pdf_binary%
            options:
                lowquality: %knp_snappy.pdf_option_lowquality%
                image-quality: %knp_snappy.pdf_option_image-quality%
                no-pdf-compression: %knp_snappy.pdf_option_no-pdf-compression%
                grayscale: %knp_snappy.pdf_option_grayscale%
        image:
            enabled:    false
    
    # genemu form conf
    genemu_form:
        date: ~
        image: ~
        tinymce:
            enabled: true
            theme:   advanced
            script_url: /bundles/myFltsExtended/js/tiny_mce/tiny_mce.js
            configs:
                content_css : /bundles/sglflts/css/invoice.css
                plugins : table
                theme_advanced_buttons1 : bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright, justifyfull,bullist,numlist,undo,redo,link,unlink
                theme_advanced_buttons2 : tablecontrols,separator
                theme_advanced_buttons3 : ~
                theme_advanced_toolbar_location : top
                theme_advanced_toolbar_align : center
                extended_valid_elements : a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]
```


### Routing  

``` yaml

    # app/config/routing.yml
    
    sgl_flts:
        resource: "@SGLFLTSBundle/Resources/config/routing/flts.yml"
        prefix:   /timesheet
    
    fos_user_security:
        resource: "@FOSUserBundle/Resources/config/routing/security.xml"
    
    fos_user_profile:
        resource: "@FOSUserBundle/Resources/config/routing/profile.xml"
        prefix: /profile
    
    fos_user_resetting:
        resource: "@FOSUserBundle/Resources/config/routing/resetting.xml"
        prefix: /resetting
    
    fos_user_change_password:
        resource: "@FOSUserBundle/Resources/config/routing/change_password.xml"
        prefix: /profile
    
    fos_user_registration:
        resource: "@FOSUserBundle/Resources/config/routing/registration.xml"
        prefix: /register
    
    genemu_image:
        resource: "@GenemuFormBundle/Resources/config/routing/image.xml"
```

### Security  

``` yaml

    # app/config/security.yml

    jms_security_extra:
        secure_all_services: false
        expressions: true
    
    security:
        encoders:
            FOS\UserBundle\Model\UserInterface: sha512
    
        providers:
            fos_userbundle:
                id: fos_user.user_provider.username
    
        firewalls:
            main:
                pattern: ^/
                form_login:
                    provider: fos_userbundle    # See providers
                    csrf_provider: form.csrf_provider
                    default_target_path: /timesheet/dashboard
    
                logout:       true
                anonymous:    true
            dev:
                pattern:  ^/(_(profiler|wdt)|css|images|js)/
                security: false
    
            login:
                pattern:  ^/login$
                security: false
    
                #anonymous: ~
                #http_basic:
                #    realm: "Secured Demo Area"
    
        role_hierarchy:
            ROLE_USER:        ~
            ROLE_BILL:        ROLE_USER   # Bill user has user roles
            ROLE_ADMIN:       ROLE_BILL   # Admin user has bill and user roles
            ROLE_SUPER_ADMIN: ROLE_ADMIN  # Super admin has admin, bill and user roles
    
        access_control:
            - { path: ^/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: ^/resetting, role: IS_AUTHENTICATED_ANONYMOUSLY }
    
            - { path: ^/register, role: ROLE_ADMIN }
            - { path: ^/profile, role: ROLE_ADMIN }
    
            - { path: ^/timesheet/invoices, roles: IS_AUTHENTICATED_ANONYMOUSLY, ip: 127.0.0.1 }  # Used by wkhtmltopdf locally
            - { path: ^/timesheet/clients, role: ROLE_ADMIN }
            - { path: ^/timesheet/projects, role: ROLE_ADMIN }
            - { path: ^/timesheet/bills, role: ROLE_BILL }
            - { path: ^/timesheet/tasks/frequent, role: ROLE_ADMIN }
            - { path: ^/timesheet/users, role: ROLE_ADMIN }
            - { path: ^/timesheet/dashboard, role: ROLE_USER }
    
            - { path: ^/timesheet, role: ROLE_USER }
    
            - { path: ^/, role: IS_AUTHENTICATED_ANONYMOUSLY }

```


### composer.json  

``` yaml

    # composer.json

    {
        # [...]

        "require": {

            # [...]

            "Friendsofsymfony/user-bundle": "1.3.*",
            "genemu/form-bundle": "2.2.*",
            "knplabs/knp-snappy-bundle": "1.*",
            "sgl/flts-bundle": "dev-master"
        },

        # [...]
    }
```


## TODO

 - Multiuser has not been tested
 - Theme layout/CSS
 - svn/git integration (revision/hash)
 - Tests
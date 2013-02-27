SGLFLTSBundle
=============

SGLFLTSBundle is a timesheet and billing application [Symfony2.1](http://symfony.com/doc/current/book/index.html) bundle mainly for freelancer.
It is a port of my sf1.0 timesheet application I used for 4 years.  

#### Notice : application is not stable, do not use in production.  


## Objects  

- **User** -           The user (freelancer) using FLTS app (FOSUserBundle user)
- **Client** -          Direct client or agency who's dealing with client
- **Project** -         e.g. Some quick neat app
- **Project Part** -    e.g. The alpha version
- **Task** -            e.g.. Backend programming, Team Coordination, Traveling, etc.
- **Work** -            e.g. Work on the user dashboard (revision 110)
- **Bill** -            Billed project part works invoice


## Installation

1. Download using [composer](http://getcomposer.org)  

    ``` yaml  
    
        # composer.json  
        "require": {  
            "sgl/flts-bundle": "dev-master"  
        }  
    ```

2. Installation requirements  
    1.1 [FOSUserBundle](https://github.com/FriendsOfSymfony/FOSUserBundle)  
    1.2 [GenemuFormBundle](https://github.com/genemu/GenemuFormBundle)  
    1.3 [KnpSnappyBundle](https://github.com/KnpLabs/KnpSnappyBundle)  
    1.4 [TinyMCE](http://www.tinymce.com/)

3. Add an admin user via FOSUserBundle registration
   Browse "http://.../register"
   Check role 'ROLE_ADMIN'

4. Remove anonymous registration access
   FLTS has a User crud

    ``` yaml

        # security.yml
        access_control: {
            - { path: ^/register, role: ROLE_ADMIN }
        }
    ```

5. After logging, create frequent tasks



## Parameters
_Example_:

``` yaml

    # parameters.yml  
    
    # SGL FLTS params
    sgl_flts.business_name:                   "Symfony dev4fun"
    sgl_flts.business_logo_src:               "/bundles/myFltsExtended/images/logos/sgl.png"
    sgl_flts.business_logo_width:             284
    sgl_flts.business_invoice_logo_src:       %sgl_flts.business_logo_src%
    sgl_flts.business_invoice_logo_width:     %sgl_flts.business_logo_width%
    sgl_flts.business_address:                "30, rue de la Visitation\nSaint-Charles-Borromée, Québec\J6E 4M8"
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

    # knp snappy params
    knp_snappy.pdf_binary:                    /usr/local/bin/wkhtmltopdf  # which wkhtmltopdf
    knp_snappy.pdf_option_lowquality:         false
    knp_snappy.pdf_option_image-quality:      100
    knp_snappy.pdf_option_no-pdf-compression: false
    knp_snappy.pdf_option_grayscale:          true

```

## Config
_Example_ :

``` yaml

    # config.yml  
    
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
    
    services:
        tax:
            class: %sgl_flts.tax_class%
            calls:
                - [ setDefaultGst, [%sgl_flts.tax_gst%] ]
                - [ setDefaultPst, [%sgl_flts.tax_pst%] ]
                - [ setDefaultHst, [%sgl_flts.tax_hst%] ]
```

## TODO

 - Recent projects quick menu
 - Multiuser has not been tested
 - Theme CSS cleanup
 - svn/git integration (revision/hash)
 - Tests
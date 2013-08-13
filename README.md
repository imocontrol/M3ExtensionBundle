# iMOControl M3 ExtensionBundle

This bundle is copied from the Sonata-Easy-Extends-Bundle which is developed by 
Thomas Rabaix. It was customized by Michael Ofner to fit generate valid application bundle
structure from each iMOControl Bundle. 
With that tiny tool it should be very easy to make all core bundles extendable.

## Installation

Add *imocontrol/m3-extenstion-bundle* to your *composer.json* or execute following command:

    $ php composer.phar require imocontrol/m3-extenstion-bundle
    
Next be sure to add following line to your AppKernel:

    <?php
    // app/AppKernel.php
    public function registerBundles()
    {
      return array(
          // ...
          new IMOControl\M3\ExtensionBundle\IMOControlM3ExtensionBundle(),
          // ...
      );
    }
    
Now the bundle is ready to use.

## Usage

After installation you can extend every iMOControlBundle to generate an Application Bundle
of it, which you can customize to your requirements.

The magic command is:

    $ php app/console imocontrol:extension:generate IMOControlM3CustomerBundle
    
This command will generate a new ApplicationIMOControlM3CustomerBundle into src/.
You can change your destination by use the *--dest=your/custom/folder* parameter. 

After that insert ApplicationIMOControlM3CustomerBundle to your AppKernel.

    <?php
    // app/AppKernel.php
    public function registerBundles()
    {
      return array(
          // ...
          new Application\IMOControl\M3\CustomerBundle\IMOControlM3CustomerBundle(),
          // ...
      );
    }

Last but not least we have to tell iMOControl to use the new application bundle.

    # app/config/imocontrol/customer.yml
    imo_control_m3_customer:
        customer_folder_root_dir: "%kernel.root_dir%/data/customers/"
		class:
			project: Application\IMOControl\M3\ProjectBundle\Entity\Project
			invoice: IMOControl\M3\InvoiceBundle\Entity\Invoice
		admin:
			customer:
				class: Application\IMOControl\M3\CustomerBundle\Admin\CustomerAdmin
				entity: IMOControl\M3\CustomerBundle\Entity\Customer
				controller: IMOControlM3CustomerBundle:Default
				translation: default
			contact:
				class: Application\IMOControl\M3\CustomerBundle\Admin\ContactAdmin
				entity: IMOControl\M3\CustomerBundle\Entity\Contact
				controller: IMOControlM3CustomerBundle:Default
				translation: default
			customer_address:
				class: Application\IMOControl\M3\CustomerBundle\Admin\CustomerAddressAdmin
				entity: IMOControl\M3\CustomerBundle\Entity\CustomerAddress
				controller: IMOControlM3CustomerBundle:Default
				translation: default

As you can see in the config above that each AdminClass, Entity, Controller and Config can
be changed in their ApplicationBundle.

## More Infos

For more infos you can read the original documentation of the SonataEasyExtendsBundle [http://sonata-project.org/bundles/easy-extends/master/doc/index.html](http://sonata-project.org/bundles/easy-extends/master/doc/index.html).

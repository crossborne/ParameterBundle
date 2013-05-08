ParameterBundle
===============

A symfony2 bundle handling various types of parameters for your entity

THIS IS JUST PROTOTYPE, feel free to contribute, the bundle still needs a lot of optimization

Installation
------------

Add crossborneParameterBundle in your composer.json:
```js
"require": {
  "crossborne/parameter-bundle": "dev-master"
}
```

Now tell composer to download the bundle by running the command:
```bash
$ php composer.phar update crossborne/parameter-bundle
```
Composer will install the bundle to your project's vendor/crossborne directory.

Enable the bundle
-----------------
Enable the bundle in the kernel:
```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new crossborne\ParameterBundle\crossborneParameterBundle(),
    );
}
```

Add this into app/config/routing.yml for parameter-lists administration
```yml
crossborne_parameter:
  resource: "@crossborneParameterBundle/Resources/config/routing.yml"
  prefix:   /parameters
```

Update your parametrized entity class to look like this:
```php
class Product implements crossborne\ParameterBundle\Model\IParametrized {

    private $parameters;
  
    public function setParameters(ParameterCollection $parameters) {
        $this->parameters = $parameters;
    }

	  public function getParameters() {
		    return $this->parameters;
    }

    public function getParametersPropertyName() {
		    return 'parameters';
    }

	  public function getRootParameterId() {
		    return null;
    }
}
```

And the entity type class:
```php
class ProductType extends AbstractType {

    private $eventSubscriber;

    public function __construct(ParametersSubscriber $eventS = null) {
        $this->eventSubscriber = $eventS;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        // $builder->add('your stuff');
        $builder->addEventSubscriber($this->eventSubscriber);
    }
}
```

When creating a form for Product entity:
```php
class ProductController extends Controller {

    private function getEventSubscriber() {
        return $this->get('crossborne_parameter.form.subscriber');
    }
    
    public function editAction($id) {
        /** some action logic */
    
        // pass the event subscriber to constructor to handle form generation
        // DONT FORGET TO PASS IT EVEN IN updateAction!
        $editForm = $this->createForm(new ProductType($this->getEventSubscriber()), $entity);
    }
}
```

And in your twig template just use:
```twig
{# Product:show.html.twig #}

{# single parameter value #}
{{ entity.parameters.getByKey('parameter_key').children.getByKey('subparameter_key').value }}

{# or list of parameters #}
{% include "crossborneParameterBundle::parameters.html.twig" with { 'parameters': entity.parameters } %}

```

Now you should be ready to go!

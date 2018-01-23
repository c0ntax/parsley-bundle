# parsley-bundle
A Symfony bundle to help wire Parsley.js into projects

## Introduction

Wouldn't it be nice if there was a way to expose [Symfony Form Constraints](https://symfony.com/doc/current/validation.html#constraints) and Entity Annotations to
the user as client side validations? Yes. Yes it would. It'd also be nice to stand on the shoulders of giants an use a well respected client side validation library
to do this! To that end, I started (about 2 years before typing this sentence) to think about using [Parsley.js](http://parsleyjs.org/) to do the validation by converting
Symfony validations into parsley ``data-parsley-*`` attributes.

So, eventually, I wrote something.

## Installation

You know the score:

```bash
composer require c0ntax/parsley-bundle
```

And don't forget to add the following to your Kernel if you're not using Flex:

```php
    public function registerBundles()
    {
        $bundles = [
            // ...
            new C0ntax\ParsleyBundle\C0ntaxParsleyBundle(),
            // ...
        ];
    }
```

## Configuration

Currently, a little configuration-light, you can add the following:

```yaml
c0ntax_parsley:
    enabled: true # Obviously set to false to switch it all off
    field:
        trigger: focusout # Set all fields to trigger validation on one or more jQuery events
```

## Supported Parsley Validations

Since this library is very much alpha, I haven't had time to add all the [validations](http://parsleyjs.org/doc/index.html#validators) in yet, so here's a list of ones that are currently supported

* Email
* Length
* MaxLength
* MinLength
* Pattern
* Max
* Min
* Required

## Usage

### Client Side Only Validations

For reasons that I can't quite imagine, you might only want to just add client side validation. To do this, simply add a parsley constraint to your form:

```php
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'textThing',
                TextType::class,
                [
                    'parsleys' => [
                        new \C0ntax\ParsleyBundle\Directive\Field\Constraint\MinLength(2, 'You need more than %s chars'),
                    ],
                ]
            )
        ;
    }
```

The above example will add a client side validation to ensure that the data is greater than or equal to 2 characters 

You can add any number of these 'parsleys'

### Form and Client Side Validation

This use-case makes a little more sense. This is for where you want to explicitly add constraints to a form and have them validate on both the client and the server (just in case the user has they javascript turned off or is messing about with your forms)

```php
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'textThing',
                TextType::class,
                [
                    'constrains' => [
                        new \Symfony\Component\Validator\Constraints\Length(['min' => 2, 'message' => 'You need more that {{ limit }} chars']),
                    ]
                ]
            )
        ;
    }
```

If you've used Symfony Forms before, you'll probably notice that there's nothing special here! What happens behind the scenes is that this library picks up Symfony constraints applied to form elements and converts them to their corresponding Parsley constraints.

So, as long as there is a mapping (See [Supported Parsley Validations](#Supported Parsley Validations)) for the Symfony constraint, it'll be added to the form automagically.

### Entity Annotation and Client Side Validation

One of the really cool things about Symfony is that you can 'configure' the validations for an Entity within the entity itself. (Some people will tell you that this is a bad thing. They are wrong in the face.)

These are also picked up by the library, so added the 'assert' to the Entity will 'just work'(tm).

```php
class Entity {
    /**
     * @Assert\Length(min=2)
     */
    private $textThing;
}
```

### 3rd-Party Entity Annotation and Client Side Validation

This is where I really wanted to get to with this library. Imagine the crazy world of the future where you have a nicely swagger/OASed spec for your API. You then use [Swagger Codegen](https://github.com/swagger-api/swagger-codegen) to produce a portable entity model.
You may even tweak Codegen so that it uses Symfony Annotations for its data validation. Well, out of the box, those annotations will now be converted to Client Side Validations too! YAY! But... What swagger/OAS doesn't account for is the error message that you might want to display
to the user. That's where the ErrorMessage Directive comes it. With this, you can add in your own error messages for annotations that you might not have 'control' over.

Let's assume that the Entity in the example above is out of your control. It's come in via a 3rd party library. You may want to give it a specific error message.

```php
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'textThing',
                TextType::class,
                [
                    'parsleys' => [
                        new \C0ntax\ParsleyBundle\Directive\Field\ConstraintErrorMessage(\C0ntax\ParsleyBundle\Directive\Field\Constraint\MinLength::class, 'You need more than %s chars'),
                    ],
                ]
            )
        ;
    }
```

*NOTE* The class passed to identify where to attache the error message is the ParsleyBundle one and not the Symfony one!

## Rolling your own

You can add your own parsley directives by simply implementing the ``DirectiveInterface``. The only requirement is that it passes back an array of attributes that will be injected into your form HTML.

## Is that it

Yes, for now. As mentioned, this is very much 'alpha' as it only supports a small subset of the Symfony Validations, for now...

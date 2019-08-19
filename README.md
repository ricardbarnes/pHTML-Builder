# pHTML Builder
Create HTML elements from PHP objects and render them in an easy and efficient way.
### Overview
```php
$html_element = new Phtml();
// creates a new empty HTML element
```
```php
$html_element = new Phtml('div');
// creates a new <div> HTML element
```
#### Why use it?
* Generate well formed HTML and XHTML code
* Make clean templates
* Forget about HTML codifications
* Save time when building HTML rendering frameworks
### Render tags
```php
$tag = new Phtml('div');
echo $tag;
```
This will print
```html
<div></div>
```
Note that the following instruction could be used as well:
```php
echo new Phtml('div');
```
But that doesn't make much sense since in the most cases elements will probably need to have attributes, inner elements and so on.

If direct full filled element printing is needed, a factory implementation may be considered.

_Notice that this may create **ANY** tag, based on the given name!_

_There is a minimalistic filter that will recognize some special tags, like `<!DOCUMENT>` or `<br/>`, and how to deal with them, but not much more in that sense. All the rest is up to the passed parameters._
#### Simple tags
```php
$html_element = new Phtml('p');
echo $html_element;
```
or
```php
$html_element = new Phtml();
$html_element->setName('p');
echo $html_element;
```
both will give
```html
<p></p>
```
Then
```php
$html_element = new Phtml('p');
$html_element->setText('some text');
echo $html_element;
```
will give
```html
<p>some text</p>
```
#### Structured tags
```php
$outer = new Phtml('div');
$outer->addAttribute('id', 'main-div');

$inner = new Phtml('p');
$inner->setText('some text');

$outer->addElement($inner);

echo $outer;
```
or
```php
$outer = new Phtml();
$outer->setName('div');
$outer->addAttribute('id', 'main-div');

$inner = new Phtml();
$inner->setName('p');
$inner->setText('some text');

$outer->addElement($inner);

echo $outer;
```
Both will print:
```html
<div id="main-div"><p>some text</p></div>
```
Of course, it can be as recursive as necessary.
#### Attributes
```php
$html_element = new Phtml('meta');

$html_element->addAttribute('charset','UTF-8');

echo $html_element;
```
This will print
```html
<meta charset="UTF-8">
```
Any attribute with the same name (in the same element) will be replaced by the last given.

_Notice that this may set **ANY** attributes, based on the given key-value pair!_

_There is a minimalistic filter that will recognize some special attributes, like `html` or even `required`, and what to do with them, but nothing else. All the rest is also up to the passed parameters._
#### Set text
```php
$html_element = new Phtml('p')

$html_element->setText('some text');

echo $html_element;
```
This will give
```html
<p>some text</p>
```
### About
I have made this to handle some stuff within another project, but I have also found this pretty interesting to share, since I didn't find something like this (that worked fine).
Well, okay, my search was not so deep but I decided that this should be a very nice coding practice for me, and, yes, definitely, it has been.

In fact, I needed to use some jQuery like function that is not implemented in that class, because I have decided to keep both works related but separated, as I think they actually are.
So, I have also created a very little jQuery like class that extends the pHTML class, with, sorry, just one -useful- method.
```php
class PhtmlQuery extends Phtml {

    function getElementByTagName($name) {
        foreach ($this->getElements() as $element) {
            if ($element->getName() === $name) {
                return $element;
            }
        }
        foreach ($this->getElements() as $e) {
            $el = $e->getElementByTagName($name);
            if ($el->getName() === $name) {
                return $el;
            }
        }
        return false;
    }

}
```
That class allowed me to fetch the `body` section of the HTML document and add some inner elements, just like any web site does.
If you would like to develop that jQuery like extending class, feel absolutely free to do.

I hope this may be useful to you.
# tollwerk ViewHelpers

[![TYPO3](https://img.shields.io/badge/TYPO3-14.3-green.svg)](https://get.typo3.org/version/14)
[![License](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)

A small collection of useful Fluid ViewHelpers for TYPO3.

## Installation

### Install extension with composer

```
composer require tollwerk/tw-viewhelpers
```

## Available ViewHelpers

### Image/InlineSvg

Render the content of a given file as an inline SVG image. The given file can be one of the following:

- TYPO3\CMS\Extbase\Domain\Model\FileReference
- TYPO3\CMS\Core\Resource\FileReference
- String of absolute or relative image path, uses `GeneralUtility::getFileAbsFileName()` to resolve the path

```html
    <!-- Relative path, starting from TYPO3 'public' directory-->
    <twvhs:image.inlineSvg image="favicon.svg" />
    <twvhs:image.inlineSvg image="fileadmin/image.svg" />

    <!-- Absolute server path -->
    <twvhs:image.inlineSvg image="/var/www/path/to/image.svg" />

    <!-- Extension directory -->
    <twvhs:image.inlineSvg image="EXT:my_extension/Resources/Public/Images/image.svg" />

    <!-- FileReference (both TYPO3 core and Extbase) -->
    <twvhs:image.inlineSvg image="{someCoreOrExtbaseObject.image}" />
    
```

### Link/TypolinkInfo

Decode a typolink string and return all properties as array.

```html
<!-- Decode typolink string. Will return an array with all properties of that link. -->
<f:debug>{twvhs:link.typolinkInfo(typolink: 't3://page?uid=1 _blank link-css-class "This is the link title" foo=bar my-special-rel-value')}</f:debug>
```

### Page/LastUpdate

Get date and author of the last change to a given page.

```html
<!-- Show last update of page with UID 1 -->
<f:debug>{twvhs:page.lastUpdate(page: 1)}</f:debug>

<!-- Show last update of given page record -->
<f:debug>{twvhs:page.lastUpdate(page: page.uid)}</f:debug>
```

### Heading

Render an HTML headline element, but with the ability to set the headline level manually or dynamically.

```html
<!-- 
Manually set the level:
<h5 class="Heading Heading--h5">Hello world</h5> 
-->
<twvhs:heading level="5" content="Hello world!" />

<!--
Nesting and dynamic level:
<h2 class="Heading Heading--h2">Hello</h2>
<h3 class="Heading Heading--h3">world!</h3> 
-->
<twvhws heading level="2" content="Hello">
    <twvhs:heading content="world!" />
</twvhws>

<!-- 
Set other CSS class than the actual level:
<h2 class="Heading Heading--h5">Hello world!</h2> 
-->
<twvhs: heading level="2" type="5" content="Hello world!" />

<!-- 
Add custom CSS classes
<h1 class="Heading Heading--h1 my-custom-class">Hello world!</h1> 
-->
<twvhs: heading class="my-custom-class" content="Hello world!" />
```




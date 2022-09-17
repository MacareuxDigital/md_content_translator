# Concrete CMS add-on: Macareux Content Translator

Concrete CMS has powerful features to manage multilingual content by 
its default. You can add language sections in your sitemap and connect 
pages between language sections, copy contents from one section to another 
language section, add a Switch Language block to provide an interface to 
switch languages for visitors, etc. Also, you can translate a user 
interface from Dashboard easily. The one last missing piece is content 
translation.

This package installs a CAT tool into your dashboard. Translators can 
translate pages from outside in-context editing, so they can focus on translate 
contents.

Also, developers can add machine translators easily.
This package contains built-in machine translators: Google Translate, DeepL

## Videos

* [How to translate contents](https://youtu.be/6Tr-8dI6G8o)
* [How to enable machine translators](https://youtu.be/90vNHARToUw)

## Features

* Create translate requests from pages
* Translate requests from dashboard
* Apply machine translation to translate requests
* Glossary for human translators

## ToDo

* Test with Basic Workflow (This package creates a new version of the page and approves it. It should work with built-in workflow system).
* Support more blocks
* Permission Keys
  * Can translate page contents (done)
  * Can discard translate request
  * Can edit translate request
  * Can publish translate request
  * Can use machine translators
* Advanced Search for translate requests
* More machine translators
* Merge this package to the core

## Not to do

* Dictionary for machine translators
  * Developers should add a custom translator that has this feature as another package. I want to keep this package as simple as I can.

## How to extend

### Make custom blocks translatable

#### Extractor Routine

Create an Extractor Routine class to get translatable content from original block.

You must implement `\Macareux\ContentTranslator\Extractor\Routine\ExtractBlockRoutineInterface` interface.

You should extend `\Macareux\ContentTranslator\Extractor\Routine\AbstractExtractBlockRoutine` class to implement the interface easily.

You need to return an instance of `\Macareux\ContentTranslator\Entity\TranslateContent` 
class only when the given block instance is the type of you want to support.

Register your extractor class to extractor manager.

```php
$manager = $app->make(\Macareux\ContentTranslator\Extractor\Routine\Manager::class);
$manager->registerRoutine(new YourCustomRoutine());
```

#### Publisher Routine

Create a Publisher Routine class to publish translated content to original page.

You must implement `\Macareux\ContentTranslator\Publisher\Routine\PublishRoutineInterface` interface.

You have to update a block of correct source type and correct identifier.
Please check built-in publisher classes.

Register your publisher class to publisher manager.

```php
$manager = $app->make(\Macareux\ContentTranslator\Publisher\Routine\Manager::class);
$manager->registerRoutine(new YourCustomRoutine());
```

### Add custom machine translators

Create a Translator class to translate content automatically.

You must implement `\Macareux\ContentTranslator\Translator\TranslatorInterface` interface.

You should extend `\Macareux\ContentTranslator\Translator\AbstractTranslator` to implement the interface easily.

You must create an element file for configuration like adding an API key.
The file structure is: `elements/content_translator/translator/{translator_handle}.php`

Finally, please install your custom translator.

```php
$manager = $app->make(\Macareux\ContentTranslator\Translator\Manager::class);
$manager->installTranslator('translator_handle', "Translator Name", 'YourTranslatorClassName');
```

## License

MIT license

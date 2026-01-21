<!--
SPDX-FileCopyrightText: (c) Respect Project Contributors
SPDX-License-Identifier: ISC
SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
-->

# TransModifier

The `|trans` modifier translates string values using a `TranslatorInterface` implementation.

## Behavior

- String values with `|trans` pipe are passed through the translator
- Non-string values delegate to the next modifier
- Missing translations return the original key unchanged

## Usage

By default, uses a `BypassTranslator` that returns the original input:

```php
use Respect\StringFormatter\PlaceholderFormatter;

$formatter = new PlaceholderFormatter(['message' => 'hello']);

echo $formatter->format('{{message|trans}}');
// Output: hello
```

## With Symfony Translator

Install `symfony/translation` and inject a real translator:

```php
use Respect\StringFormatter\PlaceholderFormatter;
use Respect\StringFormatter\Modifiers\TransModifier;
use Respect\StringFormatter\Modifiers\StringPassthroughModifier;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\ArrayLoader;

$translator = new Translator('en');
$translator->addLoader('array', new ArrayLoader());
$translator->addResource('array', ['greeting' => 'Hello World'], 'en');

$formatter = new PlaceholderFormatter(
    ['key' => 'greeting'],
    new TransModifier(new StringifyModifier(), $translator),
);

echo $formatter->format('{{key|trans}}');
// Output: Hello World
```

## Examples

| Parameters              | Template         | Output        |
| ----------------------- | ---------------- | ------------- |
| `['msg' => 'hello']`    | `{{msg\|trans}}` | `hello`       |
| `['key' => 'greeting']` | `{{key\|trans}}` | `Hello World` |

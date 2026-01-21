<!--
SPDX-FileCopyrightText: (c) Respect Project Contributors
SPDX-License-Identifier: ISC
SPDX-FileContributor: Henrique Moody <henriquemoody@gmail.com>
-->

# Commit Message Guidelines

Follow these guidelines for writing clear, consistent commit messages.

## Title

- Contains 5-116 characters
- Start with capital letter (A-Z)
- Use imperative mood ("Add feature" not "Added feature")
- No ticket numbers in title (use footer instead)
- No trailing whitespace

## Body

- Explain WHY and maybe a bit of HOW
- Empty line required between title and body
- Max 116 characters per line (except URLs, code blocks, footer annotations)
- 5 characters

## Footer

Use trailers to provide additional context when there's value to it:

- `Reference: https://github.com/example/project/issues/123` - Only when linking to a specific, relevant issue or PR
- `Assisted-by: Tool/Agent (<Model/Version>)` - When AI assistance was provided

## Examples

### Good commit message

```
Add uppercase formatter with UTF-8 support

The new UpperCaseFormatter provides proper UTF-8 handling for
international characters, converting strings to uppercase while
maintaining accent marks and special characters.

This addresses the need for proper internationalization support
when manipulating text in various languages.

Reference: https://github.com/example/project/issues/123
```

### Bad commit message

```
added upper case stuff

fixes #123
```

The bad example uses lowercase, doesn't explain the reasoning, and
includes the issue number in the title instead of the footer.

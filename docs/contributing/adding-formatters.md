# Adding New Formatters

StringFormatter is designed to be extensible. All formatters must implement the `Respect\StringFormatter\Formatter` interface.

**Steps to add a new formatter:**

1. **Create the formatter class**

Copy and rename the [TemplateFormatter.php](templates/php/src/TemplateFormatter.php) as a starting point.

2. **Create tests**

Copy and rename the [TemplateFormatterTest.php](templates/php/tests/TemplateFormatterTest.php) as a starting point.

3. **Add documentation**
   - Create `docs/YourFormatter.md` following the [formatter documentation template](templates/formatter-documentation-template.md)

4. **Update README.md**
   - Add your formatter to the "Formatters" table

# Schema.org as OpenAPI Specs

OpenAPI 2.0 compatible translation of [schema.org](https://schema.org) set of structured data.

## Caution

This package is just a playground and considered experimental and not meant for any production use.

## Run

```shell
$ composer generate
```

This will parse the RDFa specs from [https://raw.githubusercontent.com/schemaorg/schemaorg/sdo-callisto/data/schema.rdfa]
and create local versions in the `schemas` folder - one `.json` file per class/type.

## Credits

And inspired by [spatie/schema-org](https://github.com/spatie/schema-org)


<!-- markdownlint-disable MD013 -->
# Options

| Option          | Value    | Default | Description                                                                                                                |
|-----------------|----------|---------|----------------------------------------------------------------------------------------------------------------------------|
| show_constants  | boolean  | true    | whether to show class constants as readonly static variables (or just omit them completely)                                |
| show_properties | boolean  | true    | whether to show class properties                                                                                           |
| show_methods    | boolean  | true    | whether to show class or interface methods                                                                                 |
| show_private    | boolean  | true    | whether to also show private methods/properties                                                                            |
| show_protected  | boolean  | true    | whether to also show protected methods/properties                                                                          |
| add_parents     | boolean  | true    | whether to show add parent classes or interfaces                                                                           |
| only_self       | boolean  | true    | whether to only show methods/properties that are actually defined in this class (and not those merely inherited from base) |
| label_format    | string   | record  | whether to use html or record formatted labels (graphviz specific feature). Others generator may have different values     |
| indent_string   | string   | '  '    | string to indent graph statement parts                                                                                     |

All default options are available in `Bartlett\GraphUml\ClassDiagramBuilderInterface::OPTIONS_DEFAULTS` class constant

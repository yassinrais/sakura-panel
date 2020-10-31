{% set _widgets = widgets.getWidgets() %}

{% if _widgets is not null %}
    {% for widget in _widgets %}
        {{ partial(widget.getPartial()) }}
    {% endfor %}
{% endif %}
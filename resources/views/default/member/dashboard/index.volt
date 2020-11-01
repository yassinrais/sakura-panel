{% set _widgets = widgets.getWidgets() %}

{% if _widgets is not null %}
    {% for widget in _widgets %}
        {% if widget.hasPermissions(user.role_name) %}
            {{ partial(widget.getPartial()) }}
        {% endif %}
    {% endfor %}
{% endif %}
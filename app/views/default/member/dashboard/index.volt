{% set wd =  widgets.getWidgets() %}
{% if wd is not null %}
	{% for w in wd %}
		{% if w['path'] is defined %}
			{{ partial(w['path']) }}
		{% endif %}
	{% endfor %}
{% endif %}
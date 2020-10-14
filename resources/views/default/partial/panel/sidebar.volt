<ul class="navbar-nav sidebar sidebar-dark accordion sakura-bg <?=(!empty($_COOKIE['sidebar-collapse']) and $_COOKIE['sidebar-collapse'] === 'hide') ? 'toggled':'' ?>" id="accordionSidebar">

    <div class="sakura-bgx">
      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" target="_blank" href="{{url('./')}}">
        {% if site.get('app-logo',null) is not null %}
          <div class="sidebar-brand-icon sakura-icolor" >
            <img src="{{ site.get('app-logo-icon') }}" class="logo">
          </div>
          <img src="{{ site.get('app-logo') }}" class="logo sidebar-brand-text">
        {% else %}
            <div class="sidebar-brand-icon sakura-icolor" >
              <i class="fas fa-leaf"></i>
            </div>
          <div class="sidebar-brand-text mx-2">{{ site.get('app-name', getenv('APP_NAME') ? getenv('APP_NAME') : 'Sakura Panel') }}</div>
        {% endif %}
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0 pb-3">

        {% set menu = page.getMenu() %}
        {% if menu | length %}
          {% for k,m in menu %}
            {% if m['items'] is defined and m['items'] | length %}
              <div class="sidebar-heading pt-3 mt-3">
                {{ _(ucfirst(k)) }}
              </div>
              {% for it in m['items'] %}
                {% if it['sub'] is defined and it['sub'] | length %}
                <li class="nav-item {{ ( (explode(page.get('base_route'),it['url']) | length ) > 1 ) ? 'active':''}}">
                    <a class="nav-link " href="#" data-toggle="collapse" data-target="#collapse{{ loop.index }}" aria-expanded="true" aria-controls="collapse{{ loop.index }}">
                      <i class="fas fa-fw fa-{{ it['icon'] }}"></i>
                      <span>{{ _(it['title'] | e) }}</span>
                    </a>
                    <div id="collapse{{ loop.index }}" class="collapse {{ ( page.get('base_route') == it['url'] ) ? 'show':''}}" aria-labelledby="{{ _(it['title'] | e) }}" data-parent="#accordionSidebar">
                      <div class="bg-sakura py-2 mt-3 collapse-inner ">
                        <h6 class="collapse-header">Sub Menu</h6>
                        {% for sm in it['sub'] %}
                          <a class="collapse-item" href="{{ sm['url'] | e }}">{% if sm['icon'] is defined %}<i class="fa-fw {{ sm['icon'] | e }}"></i>{% endif %} {{ _(sm['title'] | e) }}</a>
                        {% endfor %}
                      </div>
                    </div>
                  </li>
                {% else %}
                <li class="nav-item {{ ( (explode(page.get('base_route'),it['url']) | length ) > 1 ) ? 'active':''}}">
                    <a class="nav-link" {% if it['url'] is defined %}href="{{ url(it['url']) }}"{% endif %} {% if it['attrs'] is defined %}{{ it['attrs'] }}{% endif %}>
                      <i class="{{ it['icon'] }} fa-fw"></i>
                      <span>{{ _(it['title'] | e) }}</span>
                    </a>
                  </li>
                {% endif %}
              {% endfor %}
            {% endif %}
          {% endfor %}
        {% endif %}

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block mt-3">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>
    </div>

</ul>
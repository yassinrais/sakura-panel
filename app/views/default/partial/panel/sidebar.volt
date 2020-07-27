<ul class="navbar-nav sidebar sidebar-dark accordion sakura-bg " id="accordionSidebar">

    <div class="sakura-bgx">
      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" target="_blank" href="{{url('./')}}">
        <div class="sidebar-brand-icon sakura-icolor" >
            <i class="fas fa-leaf"></i>
          </div>
        <div class="sidebar-brand-text mx-2">{{ site.get('app-name', getenv('APP_NAME') ? getenv('APP_NAME') : 'Sakura Panel') }}</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0 pb-3">

      {% set menu = page.getMenu() %}
      {% if menu | length %}
        {% for k,m in menu %}
          {% if m['items'] is defined and m['items'] | length %}
            <div class="sidebar-heading pt-3">
              {{ k }}
            </div>
            {% for it in m['items'] %}
            <li class="nav-item <?=(strpos(@$_GET['_url'] ?: 'url', (!empty($it->url) ? $it->url : 'unknown')) > -1) ? 'active':''; ?>">
                <a class="nav-link" {% if it.url is defined %}href="{{ url(it.url) }}"{% endif %} {% if it.attrs is defined %}{{ it.attrs }}{% endif %}>
                  <i class="{{ it.icon }} fa-fw"></i>
                  <span>{{ it.title }}</span>
                </a>
              </li>
            {% endfor %}
          {% endif %}
        {% endfor %}
      {% endif %}

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>
    </div>

</ul>
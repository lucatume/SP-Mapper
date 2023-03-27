ROOT = "$$HOME/work/tec/rbe/wordpress/wp-content/plugins"
CSV_FILE="$$HOME/Desktop/sp-map.csv"
PLUGINS = event-automator event-tickets event-tickets-plus events-community events-community-tickets events-eventbrite events-filterbar events-pro events-virtual the-events-calendar
compile:
	for plugin in $(PLUGINS); do \
  		bin/sp-mapper -q "$(ROOT)/$${plugin}/src" "$(CSV_FILE)" || exit 1; \
	done


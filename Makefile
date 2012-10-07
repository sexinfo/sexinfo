THEME_DIR = sites/all/themes/magazeen_lite
SCSS_DIR  = ${THEME_DIR}/scss
SCSS_IN   = ${SCSS_DIR}/style.scss
CSS_OUT   = ${THEME_DIR}/style.css

all: theme

theme: 
	sass ${SCSS_IN}:${CSS_OUT}
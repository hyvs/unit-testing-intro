.SILENT:
.PHONY:

# Colors
COLOR_RESET   = \033[0m
COLOR_INFO    = \033[32m
COLOR_COMMENT = \033[33m
COLOR_ERROR   = \033[31m

## Help
help:
	printf "$(COLOR_COMMENT)Usage:$(COLOR_RESET)\n"
	printf " make [target]\n\n"
	printf "$(COLOR_COMMENT)Available targets:$(COLOR_RESET)\n"
	awk '/^[a-zA-Z\-\_0-9\.@]+:/ { \
		helpMessage = match(lastLine, /^## (.*)/); \
		if (helpMessage) { \
			helpCommand = substr($$1, 0, index($$1, ":")); \
			helpMessage = substr(lastLine, RSTART + 3, RLENGTH); \
			printf " ${COLOR_INFO}%-16s${COLOR_RESET} %s\n", helpCommand, helpMessage; \
		} \
	} \
	{ lastLine = $$0 }' $(MAKEFILE_LIST)

install:
	composer install
	cd slides && npm install -g prez

prez:
	cd slides && prez --serve

## Run test "first-test"
test_1:
	vendor/bin/phpunit test/1-first-test/GrootTest.php

## Run test "mock-prophecy"
test_2:
	vendor/bin/phpunit test/2-mock-prophecy/PriceCalculatorTest.php

## Run test "test-exception"
test_3:
	vendor/bin/phpunit test/3-test-exception/PriceCalculatorTest.php

## Run test "data-provider"
test_4:
	vendor/bin/phpunit test/4-data-provider/PriceCalculatorTest.php

## Run test "complex-test"
test_5:
	vendor/bin/phpunit test/5-complex-test/VoucherFillerTest.php

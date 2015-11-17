A wrapper for [Telegram-CLI](https://github.com/vysheng/tg)

Follow the [instructions](https://github.com/vysheng/tg/blob/master/README.md) for your operating system.

You need to start the telegram-cli the first time manually in order to register the phone number.

./bin/telegram-cli

After that, start telegram-cli as a daemon from the root of the tg repo.

./bin/telegram-cli --json -dWS /tmp/tg.sck &



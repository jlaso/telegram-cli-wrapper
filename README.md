A wrapper for [Telegram-CLI](https://github.com/vysheng/tg)

Follow the [instructions](https://github.com/vysheng/tg/blob/master/README.md) for your operating system.

You need to start the telegram-cli the first time manually in order to register the phone number.

./bin/telegram-cli

After that, start telegram-cli as a daemon from the root of the tg repo.

./bin/telegram-cli --json -dWS /tmp/tg.sck &

Once installed vysheng/tg clone this project with:

```git clone https://github.com/jlaso/telegram-cli-wrapper.git```

Run ```composer install``` inside the folder repo in order to create autoload files.

Take a look on test folder to see how easy is to use the wrapper.

If you don't want to have started telegram-cli you can use TelegramCliHelper in order to start it automatically each time
is needed. To do that you need to create a config.ini in the config folder (you have config.ini.dis as a template)

All the examples in the test folder use this Helper.


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

Why TelegramCliHelper and Why to use it ?

The better way to use telegram-cli IMHO is to have started always the telegram-cli as a daemon. But maybe you want to
test something or only start it when you really need it. Becase you use a lazy cron that checks for chats once per hour or
something like that. In a real environment I would prefer to have started telegram-cli and a php script checking in loop
all chats in order to serve to users in real time.

In order that you know how to use the automated system to accept "orders" from the users or notify them by Telegram
I have prepared a little web application that you can find in /public folder.
Remember that some "orders" need defailed configuration in /config/config.ini,  openweathermap is one of them. You can
create a free account. The weather is cached in order that don't exceed the number of free calls.

Obviously all of this is only an example, you have to create the services that your web need and provide access to your
users in the way you better consider.

In order to simplify at maximum the examples I have created simpleStorage system for users that come to the web, on the
/data/user folder the users are serialized and label with the phone number, in you definitive system you have to connect
your real users with the phone number.
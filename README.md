# Call Center Demo

This uses Africa's Talking voice APIs to set up a call center. 

Deploy to a web server with the root at `public/index.php`

The following environment variables are necessary.

- `SUPPORT_PHONES_ENG`: Comma separated list of **Support** Phone Numbers for those who picked **English**
- `SALES_PHONES_ENG`: Comma separated list of **Support** Phone Numbers for those who picked **English**
- `SUPPORT_PHONES_PNG`: Comma separated list of **Support** Phone Numbers for those who picked **Pidgin**
- `SALES_PHONES_PNG`: Comma separated list of **Sales** Phone Numbers for those who picked **Pidgin**

**NOTE:** When entering multiple numbers, avoid spaces.

* `+23480XXXXXXXX,+23480YYYYYYYY` GOOD
* `+23480XXXXXXXX, +23480YYYYYYYY` BAD


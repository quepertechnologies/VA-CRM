// Line breaks for legibility only

GET https://login.microsoftonline.com/6be35ec7-ccdc-4bf1-b966-fee053d1be13/oauth2/v2.0/authorize?client_id=1e7bfd0c-266a-4658-b0aa-07594d62874b&response_type=code&redirect_uri=http%3A%2F%2Flocalhost%3A8080&response_mode=query&scope=1e7bfd0c-266a-4658-b0aa-07594d62874b%2f.default&state=12345&sso_reload=true

http://localhost:8080/?code=0.AXQAx17ja9zM8Uu5Zv7gU9G-Ewz9ex5qJlhGsKoHWU1ih0viABo.AgABAAIAAADnfolhJpSnRYB1SVj-Hgd8AgDs_wUA9P-kiq91mZnFSF4-1N--dNu8kEnSW7rUm1WRihkUDUXBuR6N-ch4sTk5VFfvZPvJ0sk3EMVgDXhN7jNwmsNIIVILmEFJNmfnMDDzQlq3TXxVAx0_DqGz-HLheGUAZfQAA7gSdqpEy8LQkIjx4Q8rmeVaCT6DGHzWGU-9DBA0LLb37hyO6fqrwIngRUd8kRx6ZyZtADRZNAXnYVpYzHymE2wCwZrQmLzzi0CzM4P41DTq2qSRYW_GWMtlvn3IX9VINRT4VVhZtuQ-gQEa-Pe1N0zK5_EZxfENrteVW7fxUiT1T-bFVStXTwdKU9QcyLQ3FAZnISq0xkH1QexZs0Q-RM4alXS4ysskds30Q1lyvHylfOpvjUZLalEEnEUP809tXeGAAmTUYQJrlfyP5FjeDSH6U4oBySLTEbg9tU13kHmTWd3lNwW_cxKSmBeRfpMARO-M3tC8ExeueTXffoqOnroFqTI15IwolANY8tVQcVxN6HIhcG3SFeR9VLw0EymW76shrcprY2Zsmk83t7_xLDxii09tVYcFw7Py4olu3igurdqgqyOSymzWilFdS6mnI_ltbykcAPGVeV6IUs-G77cj-GtWUsVLN-zISvs7qGLqajRyS9xGmL8RfiUL3EZtvg&state=12345&session_state=e154320e-39f5-4bf9-a14f-d1ba1c0a1ea9#

curl --location --request POST 'https://login.microsoftonline.com/6be35ec7-ccdc-4bf1-b966-fee053d1be13/oauth2/v2.0/token' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--data-urlencode 'client_id=1e7bfd0c-266a-4658-b0aa-07594d62874b' \
--data-urlencode 'scope=1e7bfd0c-266a-4658-b0aa-07594d62874b%2f.default' \
--data-urlencode 'code=0.AXQAx17ja9zM8Uu5Zv7gU9G-Ewz9ex5qJlhGsKoHWU1ih0viABo.AgABAAIAAADnfolhJpSnRYB1SVj-Hgd8AgDs_wUA9P_jFgslfG7TlbINpM6h-rl-ueB0dUyGTZOeXfAmnSBWhlnzlHWXaGdJtdy9hIje35f7yuDfOoy9iKHHhy5URGhzFNW5PLjfIqASkRVyKH9dUy9OFdNn6_bTksOOScLDq5YeF9hHfREw5sUngyCTOdp6pxDE1SEdOMOVYECkf-9CoYbLnx7TFDoPwnmAJP5B4ykJz_qfi3At3ZxJdmH6zXGL3GSDP2MP_rwiew2ejO5qkNeu2-PPjwHqYILhHWNMXbN9nNOmgJb6EZzctiGjMyD-N5eneSLF5K3-YGN_ipO258k8BX8Jv0ECTtdOilYnpKRwG4F-5pvqplt5zvCpJ9tcWlZ7Mp4VU1nKAD2UUgXra17IiLZTlYymRiETgiT1J786x2GVq7vaqjlZZa5Oevo7Baax0M-aZhq8qg-zVOyyNesCtm4qBvrDfqCJvf2Atk2cdFPNYHpJ5l4kd1QNpNpH6EncDdXhwfbMwm5SZW_xfINZwsj2CG67RkaUpP1XE8F_38Znp9nL5wyoIoq1hXmzfLgukm0aNWOxbg0Y2FLWij4pqlrywqf9giclRbdBv11U_6-KmmAJm4SxWZ31dluUaEyELI8wbin0UUVMbYcvgKY0ygDi0fyemsYIksD6l_0' \
--data-urlencode 'redirect_uri=http://localhost%3A8080' \
--data-urlencode 'grant_type=authorization_code' \
--data-urlencode 'client_secret=pYi8Q~ts.P04zcQJQK.SMHpzzUJpQbdPEG-M~ajW''


curl --location --request POST 'https://login.microsoftonline.com/{tenant}/oauth2/v2.0/token' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--data-urlencode 'client_id=11111111-1111-1111-1111-111111111111' \
--data-urlencode 'scope=User.Read Mail.Read' \
--data-urlencode 'refresh_token=OAAABAAAAiL9Kn2Z27UubvWFPbm0gLWQJVzCTE9UkP3pSx1aXxUjq...' \
--data-urlencode 'grant_type=refresh_token' \
--data-urlencode 'client_secret=jXoM3iz...'
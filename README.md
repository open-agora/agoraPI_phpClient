# Open Agora basic PHP client

This is a very simple client for [AgoraPI](https://www.open-agora.com/en/products/api), based on PHP with `php-curl` library.
It is intended to demonstrate and test voting functionality of AgoraPI, the Open Agora polling API.
It complements the [AgoraPI guide](https://www.open-agora.com/en/products/api-guide) and the [exhaustive documentation](https://api.open-agora.com/docs), please make sure to read these pages before starting using the present files.

**This code is not intended for production use.**

## Requirements

In order to use the files of this repository, you need the following:

1. An AgoraPI access key (and the associated token). You may register on [Open Agora website](https://www.open-agora.com/en/products/api#register) for free.
1. PHP installed on your machine (or some server). More precisely, you need PHP CLI and PHP Curl above 5.4. We recommend using PHP 7.
1. Having a running web server like **Apache** or **Nginx** is optional since you may try our code with PHP built-in server.
1. That's about it. In case of trouble you may get in touch with us: agorapi@open-agora.com

## Getting Started

Assume the downloaded files are located within folder `/agorapiTestClient/`.

1. Clone the repository or copy the files into some directory.
1. Move file `/agorapiTestClient/OA/api_dist.ini` to `/agorapiTestClient/OA/api.ini`. This file contains essential information for accessing AgoraPI.
1. Edit `/agorapiTestClient/OA/api.ini` and fill in your API token obtained from your profile at [Open Agora website](https://www.open-agora.com/user/keys).
1. Within directory `/agorapiTestClient/`, you may simply type: `php -S localhost:8888 -t .`. You should keep this server running in a console.
1. In a browser, go to [http://localhost:8888/poll_index.php](http://localhost:8888/poll_index.php).

Alternatively, you may configure **Apache** or **Nginx** to access directory `/agorapiTestClient/` from a given URL.

## Caveats

The files in this repository present **some** aspects of building a client for AgoraPI. However, we do not get deeply into every functionality of this API.

In particular, elements related to **user management** are omitted.
AgoraPI makes it possible to have users, who may create polls and cast votes (each user may cast at most a single vote for a given poll, this vote can be replaced as often as necessary).
In order to make optimal usage of AgoraPI, you should understand how users are handled, and what they are entitled to do.
It is also important with respect to anonymous voting.

:warning: AgoraPI implements a REST model, and **paginates** sets of resources (like the list of voters).
However, the curl functions in the present repository do not take pagination into account.
It is always assumed that there are less than 100 results (explicit page size used within this project).
If you intend to develop a more advanced application, **you must take pagination into account**.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.

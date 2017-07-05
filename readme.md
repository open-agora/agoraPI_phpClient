# Open Agora basic curl php client

This is a very simple client for [AgoraPI](https://www.open-agora.com/en/products/api). It is intended to demonstrate and test voting functionality of Open Agora voting API.
It complements [AgoraPI guide](https://www.open-agora.com/en/products/api-guide) and the [exhaustive documentation](https://api.open-agora.com/docs), please take make sure you have looked at these pages before starting using the present files.

**This code is not intended for production use.**

## Requirements

In order to use the files of this repository, you need the following:

1. An AgoraPI access key (and the associated token). You may register on [Open Agora website](https://www.open-agora.com/en/products/api) for free.
1. PHP installed on your machine (or some server). More precisely, you need PHP CLI and PHP Curl above 5.4. We recommand PHP 7.
1. Having a running Apache or Nginx is optional since you may try our code with PHP builtin server.
1. That's about it. In case of trouble you may get in touch with us: contact@open-agora.com

## Getting Started

Assume the downloaded files are located within folder `/agorapiTestClient/`.

1. Clone the repository or copy the files into some directory.
1. Move file `/agorapiTestClient/OA/api_dist.ini` to `/agorapiTestClient/OA/api.ini`. This file containts capital information for accessing to AgoraPI.
1. Edit `/agorapiTestClient/OA/api.ini` and type your API key token obtained from your profile at [Open Agora website](https://www.open-agora.com/user/keys).
1. Within directory `/agorapiTestClient/`, you may simply type: `php -S localhost:8888 -t .`. You should keep this server running in a console.
1. In a browser, got to [http://localhost:8888/poll_index.php](http://localhost:8888/poll_index.php).

Alternatively, you may configure Apache or Nginix to access directory `/agorapiTestClient/` from a given url.

## Caveat

The files in this repository present **some** aspects of building a client for AgoraPI. However, we do not get deeply into every functionality of this API.

In particular we skip completely elements related to user management. AgoraPI makes it possible to have users, who may create polls and cast votes
(each user may cat at most a single vote for a given poll). In order to make the best use of AgoraPI you need to understand how user work, and what they are
entilted to do. It is also important with respect to anonymous votes.

:warning: AgoraPI implements a REST model and we have chosen to page resources which may be large (like a liste of voters). However, the curl functions in the present repository do not take paging into account. It is always assumed that there are less than 10 results (this is the default value where AgoraPI starts paging the results). If you intend to develop a **more advanced application**, you **need** to take paging into account.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.

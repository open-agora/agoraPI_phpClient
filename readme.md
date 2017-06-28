# Open Agora basic curl php client

This is a very simple client for [AgoraPI](https://www.open-agora.com/en/products/api). It is intended to demonstrate and test voting functionality of Open Agora voting API.

**This code is not intended for production use.**

## Requirements

In order to use these files, you need the following:

1. An AgoraPI access key (and the associated token). You may register on [Open Agora website](https://www.open-agora.com/en/products/api) for free.
1. PHP installed on your machine (or some server). More precisely, you need PHP CLI and PHP Curl above 5.4. We recommand PHP 7.
1. Having a running Apache or Nginx is optional since you may try our code with PHP builtin server.
1. That's about it. In case of trouble you may get in touche with us: contact@open-agora.com

## Getting Started

1. Clone the repository or copy the files into some directory.
1. Move file `OA/api_dist.ini` to `OA/api.ini`.
1. Edit `OA/api.ini` and type your key token obtained from Open Agora
1. In the root directory, you may simply type: `php -S localhost:8888 -t .`. You should keep this server running in a console.
1. In a browser, got to [http://localhost:8888/poll_index.php](http://localhost:8888/poll_index.php).

## Caveat

With these files we present some aspects of building a client for AgoraPI. However, we do not get very deep into every functionality of our API.

In particular we skip completely every element related to user management. AgoraPI makes it possible to have users, who may create polls and cast vote
(each user may cat at most a vote for a given poll). In order to make the best use of AgoraPI you need to understand how user work, and what they are
entilted to do. It is also important with respect to anonymous votes.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

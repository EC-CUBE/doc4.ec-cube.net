FROM ruby:3.3

WORKDIR /site

RUN gem install bundler

EXPOSE 4000

CMD ["bundle", "exec", "jekyll", "serve", \
     "--incremental", \
     "--force_polling", \
     "--host", "0.0.0.0", \
     "--port", "4000"]
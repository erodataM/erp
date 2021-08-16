# base image
FROM node:12.2.0-alpine

# set working directory
WORKDIR /app
COPY . .
RUN apk update
RUN apk add make
# add `/app/node_modules/.bin` to $PATH
ENV PATH /app/node_modules/.bin:$PATH
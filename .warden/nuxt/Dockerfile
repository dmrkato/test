FROM node:20-alpine as build

WORKDIR /app

COPY ./package*.json ./

RUN apk add --no-cache \
      python3 \
      make \
      g++ \
      libc6-compat

RUN npm install

COPY ./ ./

EXPOSE 3000

FROM build as dev

CMD ["npm", "run", "dev"]
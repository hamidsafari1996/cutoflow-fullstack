FROM node:20-alpine

WORKDIR /usr/src/app

# Install Angular CLI globally
RUN npm i -g @angular/cli@latest

# Install dependencies first (if package files exist)
COPY package*.json ./
RUN if [ -f package.json ]; then npm ci || npm install; fi

# Copy the remaining source (mounted at runtime as well)
COPY . .

# Entrypoint to bootstrap Angular app and add Angular Material
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 4200

# Default command can be overridden by docker-compose
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]



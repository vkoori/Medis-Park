FROM heidiks/rabbitmq-delayed-message-exchange:4.1.0-management

RUN rabbitmq-plugins enable rabbitmq_shovel rabbitmq_shovel_management rabbitmq_prometheus

EXPOSE 15672 5672 15692

CMD ["rabbitmq-server"]

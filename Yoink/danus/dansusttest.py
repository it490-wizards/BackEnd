
#!/usr/bin/python3



import pika
import sys



connection = pika.BlockingConnection(
    pika.ConnectionParameters(
    host="10.144.124.131",
    port=5672,
    virtual_host="ErrorLogging",
    credentials=pika.PlainCredentials(
        username="John",
        password="test",
    ),
)
)
channel = connection.channel()

channel.exchange_declare(exchange='logs', exchange_type='fanout')

result = channel.queue_declare(queue='', exclusive=True)
queue_name = result.method.queue

channel.queue_bind(exchange='logs', queue=queue_name)


print('  Waiting for logs. To exit press CTRL+C')

def callback(ch, method, properties, body):
    print("  %r" % body)

    path = "testlogreceived.txt"

    with open(path, "a") as myfile:
        myfile.write("  %r" % body + " \n")

channel.basic_consume(
    queue=queue_name, on_message_callback=callback, auto_ack=True)

channel.start_consuming()

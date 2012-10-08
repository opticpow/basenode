#!/bin/bash

# expose individual GPIO pins
echo 18 >/sys/class/gpio/export

# Set the direction and value of reset pin
echo out >/sys/class/gpio/gpio18/direction

# Now, we.re ready to reset the JeeNode.
# I found out that the pin needs a negative transition (from 1 to 0)
# to do this, so the following three commands will do the trick:
echo 1 >/sys/class/gpio/gpio18/value
echo 0 >/sys/class/gpio/gpio18/value
echo 1 >/sys/class/gpio/gpio18/value

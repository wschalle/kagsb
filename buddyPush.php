<?php

while(1)
{
  if(rand(0, 100000) > 4000)
  {
    echo "ping";
    flush();
  }
  usleep(10);
}
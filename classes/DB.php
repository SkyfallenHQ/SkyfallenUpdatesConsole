<?php


namespace SkyfallenUpdatesConsole;


class DB
{
  public static function execute($link,$sql){
      if(mysqli_query($link,$sql)){
          return true;
      } else {
          return mysqli_error($link);
      }
  }
}
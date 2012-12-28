#
# Author:: Gergo Erdosi (<gergo@timble.net>)
# Cookbook Name:: nginx
# Attribute:: nooku-server
#
# Copyright 2012, Timble CVBA and Contributors.
#
# Licensed under the Apache License, Version 2.0 (the "License");
# you may not use this file except in compliance with the License.
# You may obtain a copy of the License at
#
#     http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.
#

include_attribute 'nginx'

default['nginx']['nooku-server']['site'] = "nooku-server"
default['nginx']['nooku-server']['dir'] = "/var/www/nooku-server"